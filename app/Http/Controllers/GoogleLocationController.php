<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class GoogleLocationController extends Controller
{
    public function ListeEtablissement()
    {
        Log::info('ListeEtablissement method called');

        $token = session('google_access_token');
        if (!$token) {
            Log::error('Access token is not set.');
            return redirect('/')->with('error', 'Access token is not set. Please login again.');
        }
        Log::info('Access token is set', ['token' => $token]);

        $cacheKey = 'google_business_locations';
        $locations = Redis::get($cacheKey);
        $accountId = env('GOOGLE_ACCOUNT_ID');

        if (!$locations) {
            Log::info('No cached locations found, fetching from API.');
            $baseUrl = "https://mybusinessbusinessinformation.googleapis.com/v1/accounts/{$accountId}/locations";
            $readMask = 'storefrontAddress,title,name,phoneNumbers';

            $allLocations = [];
            $nextPageToken = null;

            do {
                $url = $baseUrl . '?readMask=' . $readMask . ($nextPageToken ? '&pageToken=' . $nextPageToken : '');
                Log::info('Fetching data from API', ['url' => $url]);

                $response = Http::withOptions([
                    'verify' => 'E:\\PFE package\\Dashborad_GMB\\cacert.pem',
                ])->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get($url);

                if ($response->failed()) {
                    Log::error('Failed to fetch data from API', [
                        'url' => $url,
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                    return response()->json(['error' => 'Failed to fetch data from API'], 500);
                }

                $data = $response->json();
                $locations = $data['locations'] ?? [];
                foreach ($locations as &$location) {
                    $locationId = explode('/', $location['name'])[1];
                    $verificationUrl = "https://mybusinessverifications.googleapis.com/v1/locations/{$locationId}/verifications";
                    Log::info('Fetching verification data', ['verificationUrl' => $verificationUrl]);

                    $verificationResponse = Http::withOptions([
                        'verify' => 'E:\\PFE package\\Dashborad_GMB\\cacert.pem',
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
                    } else {
                        Log::error('Failed to fetch verification data', [
                            'verificationUrl' => $verificationUrl,
                            'status' => $verificationResponse->status(),
                            'response' => $verificationResponse->body()
                        ]);
                    }
                }
                $allLocations = array_merge($allLocations, $locations);
                $nextPageToken = $data['nextPageToken'] ?? null;
            } while ($nextPageToken);

            Log::info('Storing locations in Redis cache.');
            // Store in Redis with expiration time (e.g., 24 hours)
            Redis::setex($cacheKey, 86400, json_encode($allLocations));
            $locations = $allLocations;
        } else {
            Log::info('Using cached locations data.');
            $locations = json_decode($locations, true);
        }

        return view('locations', compact('locations'));
    }
}
