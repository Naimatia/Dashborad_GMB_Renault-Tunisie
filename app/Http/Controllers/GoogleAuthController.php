<?php

namespace App\Http\Controllers;

use Google_Client;
use App\Models\User;
use Google\Service\Oauth2;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $client = $this->getGoogleClient();
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");
        $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
        $client->addScope("https://www.googleapis.com/auth/business.manage");

        return redirect()->to($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        Log::info('Handling Google callback');

        $client = $this->getGoogleClient();

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($request->code);
            if (isset($accessToken['error'])) {
                Log::error('Error fetching access token: ' . $accessToken['error_description']);
                return redirect('/error')->with('error', 'Failed to log in with Google: ' . $accessToken['error_description']);
            }

            Log::info('Access token fetched successfully', ['access_token' => $accessToken]);

            // Store tokens in the session
            $this->storeTokensInSession($accessToken, $client->getRefreshToken(), $accessToken['expires_in']);

            // Get user info
            $oauthService = new Oauth2($client);
            $googleUser = $oauthService->userinfo->get();

            Log::info('Google user info fetched', ['email' => $googleUser->email]);

            // Find or create user
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => bcrypt(Str::random(16))
                ]
            );

            // Log in user
            Auth::login($user);

            Log::info('User logged in successfully', ['user_id' => $user->id]);

            // Redirect after successful login
            return redirect('/')->with('status', 'Logged in with Google successfully.');
        } catch (\Exception $e) {
            Log::error('Exception during Google login: ' . $e->getMessage());
            return redirect('/error')->with('error', 'Failed to log in with Google: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $client = $this->getGoogleClient();

        $accessToken = Session::get('google_access_token');

        if ($accessToken) {
            try {
                $client->revokeToken($accessToken);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                if ($e->getResponse()->getStatusCode() != 400 || !str_contains($e->getMessage(), 'invalid_token')) {
                    throw $e;
                }
            }
        }

        Session::forget('google_access_token');
        Session::forget('google_refresh_token');
        Session::forget('google_expires_at');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cookie::queue(Cookie::forget('google_access_token'));
        Cookie::queue(Cookie::forget('google_refresh_token'));

        return redirect('/auth/google');
    }

    private function getGoogleClient()
    {
        $client = new Google_Client();
        $client->setClientId(env('GMB_CLIENT_ID'));
        $client->setClientSecret(env('GMB_CLIENT_SECRET'));
        $client->setRedirectUri(env('GMB_REDIRECT_URI'));
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => base_path('cacert.pem')]));
        return $client;
    }

    private function storeTokensInSession($accessToken, $refreshToken, $expiresIn)
    {
        Session::put('google_access_token', $accessToken['access_token']);
        Session::put('google_refresh_token', $refreshToken);
        Session::put('google_expires_at', time() + $expiresIn);
    }
}
