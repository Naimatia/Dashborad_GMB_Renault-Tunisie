<?php

namespace App\Http\Controllers;

use Google_Client;
use Google\Service\Oauth2;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setClientId(env('GMB_CLIENT_ID'));
        $client->setClientSecret(env('GMB_CLIENT_SECRET'));
        $client->setRedirectUri(env('GMB_REDIRECT_URI'));
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");
        $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
        $client->addScope("https://www.googleapis.com/auth/business.manage");

        return redirect()->to($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(env('GMB_CLIENT_ID'));
        $client->setClientSecret(env('GMB_CLIENT_SECRET'));
        $client->setRedirectUri(env('GMB_REDIRECT_URI'));
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => base_path('cacert.pem')]));

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($request->code);
            if (isset($accessToken['error'])) {
                return redirect('/error')->with('error', 'Failed to log in with Google: ' . $accessToken['error_description']);
            }

            // Store tokens
            Session::put('google_access_token', $accessToken['access_token']);
            Session::put('google_refresh_token', $client->getRefreshToken());
            Session::put('google_expires_at', time() + $accessToken['expires_in']);

            // Get user info
            $oauthService = new Oauth2($client);
            $googleUser = $oauthService->userinfo->get();

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

            // Redirect after successful login
            return redirect('/')->with('status', 'Logged in with Google successfully.');
        } catch (\Exception $e) {
            return redirect('/error')->with('error', 'Failed to log in with Google: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(env('GMB_CLIENT_ID'));
        $client->setClientSecret(env('GMB_CLIENT_SECRET'));
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => base_path('cacert.pem')]));

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
}
