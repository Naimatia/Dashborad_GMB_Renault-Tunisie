<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class DashbordController extends Controller
{
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

        // Redis cache keys
        $currentYearCacheKey = "performance_{$id}_{$startYear}_{$startMonth}_{$startDay}_{$endYear}_{$endMonth}_{$originalEndDay}";

        // Try to get cached data from Redis
        $currentYearPerformanceData = Redis::get($currentYearCacheKey);

        if (!$currentYearPerformanceData) {
            // Performance API for this YEAR
            $currentYearUrl = "https://businessprofileperformance.googleapis.com/v1/locations/{$id}:fetchMultiDailyMetricsTimeSeries?dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_SEARCH&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_SEARCH&dailyMetrics=WEBSITE_CLICKS&dailyMetrics=CALL_CLICKS&dailyMetrics=BUSINESS_DIRECTION_REQUESTS&dailyMetrics=BUSINESS_BOOKINGS&dailyMetrics=BUSINESS_CONVERSATIONS&dailyRange.start_date.year={$startYear}&dailyRange.start_date.month={$startMonth}&dailyRange.start_date.day={$startDay}&dailyRange.end_date.year={$endYear}&dailyRange.end_date.month={$endMonth}&dailyRange.end_date.day={$originalEndDay}";

            $currentYearResponse = Http::withOptions([
                'verify' => 'E:\PFE package\Dashborad_GMB\cacert.pem',
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($currentYearUrl);

            if ($currentYearResponse->failed()) {
                // Handle API request failure if needed
                return response()->json(['error' => 'Failed to fetch data from API'], 500);
            }

            // Decode the JSON response
            $currentYearPerformanceData = $currentYearResponse->json();

            // Store in Redis with expiration time (e.g., 24 hours)
            Redis::setex($currentYearCacheKey, 7200, json_encode($currentYearPerformanceData));
        } else {
            // Decode cached data from Redis
            $currentYearPerformanceData = json_decode($currentYearPerformanceData, true);
        }

        // Process the data as needed, e.g., organize for charting

        // Return the view or JSON response
        if ($request->ajax()) {
            return response()->json([
                'currentYearData' => $currentYearPerformanceData,
                'startDate' => "{$startDay}-{$startMonth}-{$startYear}",
                'endDate' => "{$originalEndDay}-{$endMonth}-{$endYear}"
            ]);
        } else {
            return view('dashbord', compact('currentYearPerformanceData'));
        }
    }

    public function ListeLocalisation(Request $request)
    {
        $token = session('google_access_token');

        if (!$token) {
            return redirect('/')->with('error', 'Access token is not set. Please login again.');
        }

        // Retrieve date range from request query parameters
        $startYear = $request->query('startYear');
        $startMonth = $request->query('startMonth');
        $startDay = $request->query('startDay');
        $endYear = $request->query('endYear');
        $endMonth = $request->query('endMonth');
        $endDay = $request->query('endDay');

        // Redis cache key for the list of locations
        $locationsCacheKey = "locations_{$startYear}_{$startMonth}_{$startDay}_{$endYear}_{$endMonth}_{$endDay}";

        // Try to get cached data from Redis
        $cachedData = Redis::get($locationsCacheKey);

        if ($cachedData) {
            // Decode cached data from Redis
            $cachedData = json_decode($cachedData, true);
            $coordinates = $cachedData['coordinates'];
            $verifiedCount = $cachedData['verifiedCount'];
            $performanceData = $cachedData['performanceData'];
        } else {
            $accountId = env('GOOGLE_ACCOUNT_ID');

            // Base URL for Google My Business API
            $baseUrl = 'https://mybusinessbusinessinformation.googleapis.com/v1/accounts/{$accountId}/locations';
            $readMask = 'latlng,title,name';

            // Array to store locations' data
            $coordinates = [];

            // Variable to keep track of the number of verified locations
            $verifiedCount = 0;

            // Array to store performance data for each location
            $performanceData = [];

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

            // Store the data in Redis with expiration time (e.g., 24 hours)
            Redis::setex($locationsCacheKey, 21600, json_encode([
                'coordinates' => $coordinates,
                'verifiedCount' => $verifiedCount,
                'performanceData' => $performanceData
            ]));
        }

        if ($request->ajax()) {
            // For AJAX requests, return JSON data
            return response()->json([
                'coordinates' => $coordinates,
                'verifiedCount' => $verifiedCount,
                'performanceData' => $performanceData
            ]);
        } else {
            // For regular requests, return the view with the data
            return view('dashbord', compact('coordinates', 'verifiedCount', 'performanceData'));
        }
    }
}
