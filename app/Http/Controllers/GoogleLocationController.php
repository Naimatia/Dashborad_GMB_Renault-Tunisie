<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class GoogleLocationController extends Controller
{
    public function ListeEtablissement()
    {
        $token = session('google_access_token');
        if (!$token) {
            return redirect('/')->with('error', 'Access token is not set. Please login again.');
        }

        $cacheKey = 'google_business_locations';
        $locations = Redis::get($cacheKey);
        $accountId = env('GOOGLE_ACCOUNT_ID');

        if (!$locations) {
            $baseUrl = 'https://mybusinessbusinessinformation.googleapis.com/v1/accounts/{$accountId}/locations';
            $readMask = 'storefrontAddress,title,name,phoneNumbers';

            $allLocations = [];
            $nextPageToken = null;

            do {
                $url = $baseUrl . '?readMask=' . $readMask . ($nextPageToken ? '&pageToken=' . $nextPageToken : '');
                $response = Http::withOptions([
                    'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
                ])->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get($url);

                if ($response->failed()) {
                    return redirect('/')->with('error', 'Failed to fetch locations from Google My Business.');
                }

                $data = $response->json();
                $locations = $data['locations'] ?? [];
                foreach ($locations as &$location) {
                    $locationId = explode('/', $location['name'])[1];
                    $verificationUrl = 'https://mybusinessverifications.googleapis.com/v1/locations/' . $locationId . '/verifications';
                    $verificationResponse = Http::withOptions([
                        'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
                    ])->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                    ])->get($verificationUrl);

                    $location['verified'] = false;
                    if ($verificationResponse->successful()) {
                        $verificationData = $verificationResponse->json();
                        foreach ($verificationData['verifications'] as $verification) {
                            if ($verification['state'] === 'COMPLETED') {
                                $location['verified'] = true;
                                break;
                            }
                        }
                    }
                }
                $allLocations = array_merge($allLocations, $locations);
                $nextPageToken = $data['nextPageToken'] ?? null;
            } while ($nextPageToken);

            // Store in Redis with expiration time (e.g., 10 minutes)
            Redis::setex($cacheKey, 86400, json_encode($allLocations));
            $locations = $allLocations;
        } else {
            $locations = json_decode($locations, true);
        }

        return view('locations', compact('locations'));
    }
}
