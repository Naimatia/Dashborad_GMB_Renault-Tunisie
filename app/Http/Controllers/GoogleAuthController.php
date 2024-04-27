<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

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
                return redirect('/')->with('error', 'Failed to log in with Google: ' . $accessToken['error_description']);
            }
            // Assurez-vous de stocker juste le token d'accès, pas tout le tableau
            Session::put('google_access_token', $accessToken['access_token']);
            Session::put('google_refresh_token', $client->getRefreshToken());
            // Calculer et stocker le temps d'expiration
            Session::put('google_expires_at', time() + $accessToken['expires_in']);
            return redirect('/')->with('status', 'Logged in with Google successfully.');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Failed to log in with Google: ' . $e->getMessage());
        }
    }





    public function ListeEtablissement()
    {

        $token = session('google_access_token');
        if (!$token) {
            return redirect('/')->with('error', 'Access token is not set. Please login again.');
        }

        // Base URL for Google My Business API
        $baseUrl = 'https://mybusinessbusinessinformation.googleapis.com/v1/accounts/110996943980669817062/locations';
        $readMask = 'storefrontAddress,title,name,phoneNumbers';

        $locations = [];
        $nextPageToken = null;

        // Loop to fetch all pages
        do {
            // Construct the API URL with the readMask and nextPageToken
            $url = $baseUrl . '?readMask=' . $readMask;
            if ($nextPageToken) {
                $url .= '&pageToken=' . $nextPageToken;
            }

            // Make the API request
            $response = Http::withOptions([
                'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($url);

            if ($response->failed()) {
                return redirect('/')->with('error', 'Failed to fetch locations from Google My Business.');
            }

            // Get the JSON data from the response
            $data = $response->json();

            // Append the current page's locations to the locations array
            if (isset($data['locations'])) {
                foreach ($data['locations'] as &$location) {
                    // Get the location ID from the name field
                    $locationId = explode('/', $location['name'])[1];

                    // Make the request to the Google My Business Verifications API
                    $verificationUrl = 'https://mybusinessverifications.googleapis.com/v1/locations/' . $locationId . '/verifications';

                    // Make the API request to check the verification status
                    $verificationResponse = Http::withOptions([
                        'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
                    ])->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                    ])->get($verificationUrl);


                    if ($verificationResponse->successful()) {
                        $verificationData = $verificationResponse->json();

                        // Check if the verification is completed
                        if (isset($verificationData['verifications']) && !empty($verificationData['verifications'])) {
                            foreach ($verificationData['verifications'] as $verification) {
                                if ($verification['state'] === 'COMPLETED') {
                                    // The location is verified
                                    $location['verified'] = true;
                                    break;
                                }
                            }
                        }
                    }

                    // If the verification status was not found, the location is not verified
                    if (!isset($location['verified'])) {
                        $location['verified'] = false;
                    }
                }
            }

            // Append the current page's locations to the locations array
            $locations = array_merge($locations, $data['locations']);

            // Get the nextPageToken from the response
            $nextPageToken = $data['nextPageToken'] ?? null;
        } while ($nextPageToken); // Continue the loop as long as there's a nextPageToken
        // dd($locations);
        // Return the locations data to the view with verification status
        return view('locations', compact('locations'));
    }




    public function PerfermanceAPI(Request $request, $id)
    {

        // Retrieve query parameters
        $startYear = $request->query('startYear');
        $startMonth = $request->query('startMonth');
        $endYear = $request->query('endYear');
        $endMonth = $request->query('endMonth');
        $startDay = $request->query('startDay');
        $endDay = $request->query('endDay');

        // Store the original endDay value before any modifications
        $originalEndDay = $endDay;

        $startLastYear = $startYear - 1;
        $endLastYear = $endYear - 1;

        $token = session('google_access_token');

        if (!$token) {
            return redirect('/')->with('error', 'Access token is not set. Please login again.');
        }

        // Performance API for this YEAR
        $currentYearUrl = "https://businessprofileperformance.googleapis.com/v1/locations/{$id}:fetchMultiDailyMetricsTimeSeries?dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_SEARCH&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_SEARCH&dailyMetrics=WEBSITE_CLICKS&dailyMetrics=CALL_CLICKS&dailyMetrics=BUSINESS_DIRECTION_REQUESTS&dailyMetrics=BUSINESS_BOOKINGS&dailyMetrics=BUSINESS_CONVERSATIONS&dailyRange.start_date.year={$startYear}&dailyRange.start_date.month={$startMonth}&dailyRange.start_date.day={$startDay}&dailyRange.end_date.year={$endYear}&dailyRange.end_date.month={$endMonth}&dailyRange.end_date.day={$endDay}";

        // Check if the start year is a leap year and if the start month is February
        if (($startLastYear % 4 == 0 && $startLastYear % 100 != 0) || $startLastYear % 400 == 0) {
            if ($startMonth == 2) {
                // If it's February in a leap year, set the end day to 29
                $endDay = 29;
            }
        } else {
            if ($startMonth == 2) {
                // If it's February in a non-leap year, set the end day to 28
                $endDay = 28;
            }
        }
        // Performance API for last YEAR
        $lastYearUrl = "https://businessprofileperformance.googleapis.com/v1/locations/{$id}:fetchMultiDailyMetricsTimeSeries?dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_SEARCH&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_SEARCH&dailyMetrics=WEBSITE_CLICKS&dailyMetrics=CALL_CLICKS&dailyMetrics=BUSINESS_DIRECTION_REQUESTS&dailyMetrics=BUSINESS_BOOKINGS&dailyMetrics=BUSINESS_CONVERSATIONS&dailyRange.start_date.year={$startLastYear}&dailyRange.start_date.month={$startMonth}&dailyRange.start_date.day={$startDay}&dailyRange.end_date.year={$endLastYear}&dailyRange.end_date.month={$endMonth}&dailyRange.end_date.day={$endDay}";

        // Make the API calls
        $currentYearResponse = Http::withOptions([
            'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
        ])->withHeaders([
            'Authorization' => 'Bearer ' . session('google_access_token'),
        ])->get($currentYearUrl);

        $lastYearResponse = Http::withOptions([
            'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
        ])->withHeaders([
            'Authorization' => 'Bearer ' . session('google_access_token'),
        ])->get($lastYearUrl);


        // Decode the JSON responses
        $currentYearPerformanceData = $currentYearResponse->json();
        $lastYearPerformanceData = $lastYearResponse->json();

        // Process the data as needed, e.g., organize for charting

        // Return the view with the performance data
        if ($request->ajax()) {
            // For AJAX requests, return JSON data for both years
            return response()->json([
                'currentYearData' => $currentYearPerformanceData,
                'lastYearData' => $lastYearPerformanceData,
                'startDate' => $startDay . '-' . $startMonth . '-' . $startYear,
                'endDate' =>  $originalEndDay . '-' . $endMonth . '-' . $endYear
            ]);
        } else {
            // For regular requests, return the view with both years' data
            return view('fiche', compact('currentYearPerformanceData', 'lastYearPerformanceData'));
        }
    }

    public function ALLReview()
    {

        $token = session('google_access_token'); // Votre jeton d'accès réel va ici

        if (!$token) {
            return redirect('/')->with('error', 'Access token is not set. Please login again.');
        }

        $url = "https://mybusiness.googleapis.com/v4/accounts/110996943980669817062/locations/8520460341050881890/reviews";

        $response = Http::withOptions([
            'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
        ])->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($url);

        if ($response->failed()) {
            return redirect('/')->with('error', 'Failed to fetch locations from Google My Business.');
        }

        $reviews = $response->json();
        // Affichage de la réponse pour déboguer
        // dd($response->json());

        // Faites quelque chose avec les données, par exemple, les retourner ou les afficher
        return view('fiche', compact('reviews'));
    }


    public function GetPerfermanceReviews(Request $request, $id)
    {
        // Retrieve query parameters for performance
        $startYear = $request->query('startYear');
        $startMonth = $request->query('startMonth');
        $endYear = $request->query('endYear');
        $endMonth = $request->query('endMonth');
        $startDay = $request->query('startDay');
        $endDay = $request->query('endDay');


        // Store the original endDay value before any modifications
        $originalEndDay = $endDay;

        $startLastYear = $startYear - 1;
        $endLastYear = $endYear - 1;

        $token = session('google_access_token');

        if (!$token) {
            return redirect('/')->with('error', 'Access token is not set. Please login again.');
        }

        // Performance API URL for this YEAR
        $currentYearUrl = "https://businessprofileperformance.googleapis.com/v1/locations/{$id}:fetchMultiDailyMetricsTimeSeries?dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_SEARCH&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_SEARCH&dailyMetrics=WEBSITE_CLICKS&dailyMetrics=CALL_CLICKS&dailyMetrics=BUSINESS_DIRECTION_REQUESTS&dailyMetrics=BUSINESS_BOOKINGS&dailyMetrics=BUSINESS_CONVERSATIONS&dailyRange.start_date.year={$startYear}&dailyRange.start_date.month={$startMonth}&dailyRange.start_date.day={$startDay}&dailyRange.end_date.year={$endYear}&dailyRange.end_date.month={$endMonth}&dailyRange.end_date.day={$endDay}";

        // Check if the start year is a leap year and if the start month is February
        if (($startLastYear % 4 == 0 && $startLastYear % 100 != 0) || $startLastYear % 400 == 0) {
            if ($startMonth == 2) {
                // If it's February in a leap year, set the end day to 29
                $endDay = 29;
            }
        } else {
            if ($startMonth == 2) {
                // If it's February in a non-leap year, set the end day to 28
                $endDay = 28;
            }
        }

        // Performance API URL for last YEAR
        $lastYearUrl = "https://businessprofileperformance.googleapis.com/v1/locations/{$id}:fetchMultiDailyMetricsTimeSeries?dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_SEARCH&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_SEARCH&dailyMetrics=WEBSITE_CLICKS&dailyMetrics=CALL_CLICKS&dailyMetrics=BUSINESS_DIRECTION_REQUESTS&dailyMetrics=BUSINESS_BOOKINGS&dailyMetrics=BUSINESS_CONVERSATIONS&dailyRange.start_date.year={$startLastYear}&dailyRange.start_date.month={$startMonth}&dailyRange.start_date.day={$startDay}&dailyRange.end_date.year={$endLastYear}&dailyRange.end_date.month={$endMonth}&dailyRange.end_date.day={$endDay}";

        // Make the API call for performance data
        $currentYearResponse = Http::withOptions([
            'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
        ])->withHeaders([
            'Authorization' => 'Bearer ' . session('google_access_token'),
        ])->get($currentYearUrl);

        $lastYearResponse = Http::withOptions([
            'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
        ])->withHeaders([
            'Authorization' => 'Bearer ' . session('google_access_token'),
        ])->get($lastYearUrl);

        // Decode the JSON responses for performance data
        $currentYearPerformanceData = $currentYearResponse->json();
        $lastYearPerformanceData = $lastYearResponse->json();

        // Process the data as needed for performance


        // Make the initial API call for reviews data
        $url = "https://mybusiness.googleapis.com/v4/accounts/110996943980669817062/locations/{$id}/reviews";
        $allReviews = [];

        do {
            // Make the API call to fetch reviews
            $response = Http::withOptions([
                'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($url);

            if ($response->failed()) {
                return redirect('/')->with('error', 'Failed to fetch reviews from Google My Business.');
            }

            $reviews = $response->json();


            // Add reviews from this page to the array of all reviews
            $allReviews = array_merge($allReviews, $reviews['reviews']);

            $totalReviewCount = $reviews['totalReviewCount'];
            $averageRating = $reviews['averageRating'];


            // Check if there are more pages
            if (isset($reviews['nextPageToken'])) {
                // Set the next page token for the next request
                $nextPageToken = $reviews['nextPageToken'];
                // Update the URL for the next page request
                $url = "https://mybusiness.googleapis.com/v4/accounts/110996943980669817062/locations/{$id}/reviews?pageToken={$nextPageToken}";
            } else {
                // If there are no more pages, break the loop
                break;
            }
        } while (true);


        // Return the view with the data
        if ($request->ajax()) {
            // For AJAX requests, return JSON data
            return response()->json([
                'currentYearData' => $currentYearPerformanceData,
                'lastYearData' => $lastYearPerformanceData,
                'reviews' => $allReviews,
                'startDate' => $startDay . '-' . $startMonth . '-' . $startYear,
                'endDate' =>  $originalEndDay . '-' . $endMonth . '-' . $endYear
            ]);
        } else {
            // For regular requests, return the view with data
            return view('fiche', compact('currentYearPerformanceData', 'lastYearPerformanceData', 'allReviews', 'totalReviewCount', 'averageRating', 'token', 'id'));
        }
    }

    public function ListeLocalisation(Request $request)
    {
        $token = session('google_access_token');
        if (!$token) {
            return redirect('/')->with('error', 'Access token is not set. Please login again.');
        }

        // Base URL for Google My Business API
        $baseUrl = 'https://mybusinessbusinessinformation.googleapis.com/v1/accounts/110996943980669817062/locations';
        $readMask = 'latlng,title,name';

        // Array to store locations' data
        $coordinates = [];

        // Variable to keep track of the number of verified locations
        $verifiedCount = 0;

        // Array to store performance data for each location
        $performanceData = [];

        // Retrieve date range from request query parameters
        $startYear = $request->query('startYear');
        $startMonth = $request->query('startMonth');
        $startDay = $request->query('startDay');
        $endYear = $request->query('endYear');
        $endMonth = $request->query('endMonth');
        $endDay = $request->query('endDay');

        // Loop to fetch all pages
        $nextPageToken = null;
        do {
            // Construct the API URL with the readMask and nextPageToken
            $url = $baseUrl . '?readMask=' . $readMask;
            if ($nextPageToken) {
                $url .= '&pageToken=' . $nextPageToken;
            }

            $response = Http::withOptions([
                'verify' => 'E:\\PFE package\\Dashborad_GMB\\cacert.pem',
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($url);

            if ($response->failed()) {
                return redirect('/')->with('error', 'Failed to fetch data from Google My Business.');
            }

            // Get the JSON data from the response
            $data = $response->json();

            // Process each location
            foreach ($data['locations'] as $location) {
                // Add location data to coordinates array
                $coordinates[] = $location;

                // Extract location ID from the location name
                $locationId = explode('/', $location['name'])[1];

                // Fetch and add performance data using the PerfermanceAPI function
                // Pass the date range parameters to the PerfermanceAPI function
                $performance = $this->PerfermanceAPI($request, $locationId);

                $performanceData[$locationId] = [
                    'title' => $location['title'],
                    'performance' => $performance
                ];


                // Check verification status
                $verificationUrl = "https://mybusinessverifications.googleapis.com/v1/locations/{$locationId}/verifications";
                $verificationResponse = Http::withOptions([
                    'verify' => 'E:\\PFE package\\Dashborad_GMB\\cacert.pem',
                ])->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get($verificationUrl);

                if ($verificationResponse->successful()) {
                    $verificationData = $verificationResponse->json();
                    $verifications = $verificationData['verifications'] ?? [];

                    // Check verification state for each location
                    foreach ($verifications as $verification) {
                        if ($verification['state'] === 'COMPLETED') {
                            $verifiedCount++;
                            break;
                        }
                    }
                }
            }

            // Get nextPageToken from response
            $nextPageToken = $data['nextPageToken'] ?? null;
        } while ($nextPageToken);

    if ($request->ajax()) {
        // For AJAX requests, return JSON data for both years
        return response()->json([
            'coordinates' => $coordinates,
        'verifiedCount' => $verifiedCount,
        'performanceData' => $performanceData
        ]);
    } else {
        // For regular requests, return the view with both years' data
        return view('dashbord', compact('coordinates', 'verifiedCount', 'performanceData'));
    }
    }
}
