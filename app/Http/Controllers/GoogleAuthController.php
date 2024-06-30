<?php

namespace App\Http\Controllers;

use Google_Client;
use App\Models\User;
use Google\Service\Oauth2;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

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
        // Ajout du scope nécessaire pour Google My Business
        $client->addScope("https://www.googleapis.com/auth/business.manage");

        return redirect()->to($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(env('GMB_CLIENT_ID'));
        $client->setClientSecret(env('GMB_CLIENT_SECRET'));
        $client->setRedirectUri(env('GMB_REDIRECT_URI'));
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem']));

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($request->code);
            if (isset($accessToken['error'])) {
                return redirect('/error')->with('error', 'Failed to log in with Google: ' . $accessToken['error_description']);
            }

            // Stockage des tokens
            Session::put('google_access_token', $accessToken['access_token']);
            Session::put('google_refresh_token', $client->getRefreshToken());
            Session::put('google_expires_at', time() + $accessToken['expires_in']);

            // Obtenir les informations de l'utilisateur
            $oauthService = new Oauth2($client);
            $googleUser = $oauthService->userinfo->get();

            // Recherche ou création de l'utilisateur
            $user = User::firstOrCreate([
                'email' => $googleUser->email,
            ], [
                'name' => $googleUser->name,
                // Générer un mot de passe par défaut si l'utilisateur est créé
                'password' => bcrypt(Str::random(16)),
                // Mettez à jour d'autres informations utilisateur si nécessaire
            ]);


            // Connexion de l'utilisateur
            Auth::login($user);

            // Redirection après une connexion réussie
            return redirect('/')->with('status', 'Logged in with Google successfully.');
        } catch (\Exception $e) {
            return redirect('/error')->with('error', 'Failed to log in with Google: ' . $e->getMessage());
        }
    }




    public function logout(Request $request)
    {
        // Instancier le client Google
        $client = new \Google_Client();
        $client->setClientId(env('GMB_CLIENT_ID'));
        $client->setClientSecret(env('GMB_CLIENT_SECRET'));
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem']));


        // Obtenir le token d'accès stocké
        $accessToken = Session::get('google_access_token');

        if ($accessToken) {
            // Révoquer le token d'accès auprès de Google
            $client->revokeToken($accessToken);
        }

        // Supprimer les tokens et données de session
        Session::forget('google_access_token');
        Session::forget('google_refresh_token');
        Session::forget('google_expires_at');

        // Nettoyer d'autres données de session liées à l'utilisateur (si nécessaire)

        // Invalider la session
        $request->session()->invalidate();
        // Régénérer le token de session
        $request->session()->regenerateToken();

        // Supprimer tous les cookies liés à l'authentification
        Cookie::queue(Cookie::forget('google_access_token'));
        Cookie::queue(Cookie::forget('google_refresh_token'));
        Cookie::queue(Cookie::forget('google_user_info'));

        // Rediriger vers la page de connexion de Google
        return redirect('/auth/google');
    }


}
