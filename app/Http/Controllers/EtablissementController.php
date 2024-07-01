<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class etablissementController extends Controller
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

        // Redis cache keys
        $currentYearCacheKey = "performance_{$id}_{$startYear}_{$startMonth}_{$startDay}_{$endYear}_{$endMonth}_{$originalEndDay}";
        $lastYearCacheKey = "performance_{$id}_{$startLastYear}_{$startMonth}_{$startDay}_{$endLastYear}_{$endMonth}_{$endDay}";

        // Try to get cached data from Redis
        $currentYearPerformanceData = Redis::get($currentYearCacheKey);
        $lastYearPerformanceData = Redis::get($lastYearCacheKey);

        if (!$currentYearPerformanceData || !$lastYearPerformanceData) {

            // Performance API URL for this YEAR
            $currentYearUrl = "https://businessprofileperformance.googleapis.com/v1/locations/{$id}:fetchMultiDailyMetricsTimeSeries?dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_DESKTOP_SEARCH&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_MAPS&dailyMetrics=BUSINESS_IMPRESSIONS_MOBILE_SEARCH&dailyMetrics=WEBSITE_CLICKS&dailyMetrics=CALL_CLICKS&dailyMetrics=BUSINESS_DIRECTION_REQUESTS&dailyMetrics=BUSINESS_BOOKINGS&dailyMetrics=BUSINESS_CONVERSATIONS&dailyRange.start_date.year={$startYear}&dailyRange.start_date.month={$startMonth}&dailyRange.start_date.day={$startDay}&dailyRange.end_date.year={$endYear}&dailyRange.end_date.month={$endMonth}&dailyRange.end_date.day={$endDay}";

            // Performance API URL for last YEAR
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

            // Store in Redis with expiration time (e.g., 24 hours)
            Redis::setex($currentYearCacheKey, 21600, json_encode($currentYearPerformanceData));
            Redis::setex($lastYearCacheKey, 21600, json_encode($lastYearPerformanceData));
        } else {
            // Decode cached data from Redis
            $currentYearPerformanceData = json_decode($currentYearPerformanceData, true);
            $lastYearPerformanceData = json_decode($lastYearPerformanceData, true);
        }

        // Return the view or JSON response
        if ($request->ajax()) {
            // For AJAX requests, return JSON data
            return response()->json([
                'currentYearData' => $currentYearPerformanceData,
                'lastYearData' => $lastYearPerformanceData,
                'startDate' => "{$startDay}-{$startMonth}-{$startYear}",
                'endDate' => "{$originalEndDay}-{$endMonth}-{$endYear}"
            ]);
        } else {
            // For regular requests, return the view with both years' data
            return view('fiche', compact('currentYearPerformanceData', 'lastYearPerformanceData'));
        }
    }



    public function GetPerfermanceReviews(Request $request, $id)
    {
        $accountId = env('GOOGLE_ACCOUNT_ID');

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

        // Redis cache keys for performance data
        $currentYearCacheKey = "performance_{$id}_{$startYear}_{$startMonth}_{$startDay}_{$endYear}_{$endMonth}_{$originalEndDay}";
        $lastYearCacheKey = "performance_{$id}_{$startLastYear}_{$startMonth}_{$startDay}_{$endLastYear}_{$endMonth}_{$endDay}";

        // Try to get cached performance data from Redis
        $currentYearPerformanceData = Redis::get($currentYearCacheKey);
        $lastYearPerformanceData = Redis::get($lastYearCacheKey);

        if (!$currentYearPerformanceData || !$lastYearPerformanceData) {
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

            // Make the API calls for performance data
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

            // Store in Redis with expiration time (e.g., 24 hours)
            Redis::setex($currentYearCacheKey, 21600, json_encode($currentYearPerformanceData));
            Redis::setex($lastYearCacheKey, 21600, json_encode($lastYearPerformanceData));
        } else {
            // Decode cached data from Redis
            $currentYearPerformanceData = json_decode($currentYearPerformanceData, true);
            $lastYearPerformanceData = json_decode($lastYearPerformanceData, true);
        }

        // Initialize variables for reviews data
        $allReviews = [];
        $totalReviewCount = 0;
        $averageRating = 0;

        // Redis cache key for reviews data
        $reviewsCacheKey = "reviews_{$id}";

        // Try to get cached reviews data from Redis
        $cachedReviews = Redis::get($reviewsCacheKey);

        if (!$cachedReviews) {
            // If not cached, make the initial API call for reviews data
            $url = "https://mybusiness.googleapis.com/v4/accounts/{$accountId}/locations/{$id}/reviews";

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
                if (isset($reviews['reviews'])) {
                    $allReviews = array_merge($allReviews, $reviews['reviews']);
                }

                $totalReviewCount = $reviews['totalReviewCount'] ?? $totalReviewCount;
                $averageRating = $reviews['averageRating'] ?? $averageRating;

                // Check if there are more pages
                if (isset($reviews['nextPageToken'])) {
                    // Set the next page token for the next request
                    $nextPageToken = $reviews['nextPageToken'];
                    // Update the URL for the next page request
                    $url = "https://mybusiness.googleapis.com/v4/accounts/{$accountId}/locations/{$id}/reviews?pageToken={$nextPageToken}";
                } else {
                    // If there are no more pages, break the loop
                    break;
                }
            } while (true);

            // Cache the reviews data in Redis for 24 hours
            Redis::setex($reviewsCacheKey, 21600, json_encode(['reviews' => $allReviews, 'totalReviewCount' => $totalReviewCount, 'averageRating' => $averageRating]));
        } else {
            // Decode cached reviews data from Redis
            $cachedReviews = json_decode($cachedReviews, true);
            $allReviews = $cachedReviews['reviews'] ?? [];
            $totalReviewCount = $cachedReviews['totalReviewCount'] ?? 0;
            $averageRating = $cachedReviews['averageRating'] ?? 0;
        }

        // Return the view or JSON response
        if ($request->ajax()) {
            // For AJAX requests, return JSON data
            return response()->json([
                'currentYearData' => $currentYearPerformanceData,
                'lastYearData' => $lastYearPerformanceData,
                'reviews' => $allReviews,
                'startDate' => "{$startDay}-{$startMonth}-{$startYear}",
                'endDate' => "{$originalEndDay}-{$endMonth}-{$endYear}"
            ]);
        } else {
            // For regular requests, return the view with data
            return view('fiche', compact('currentYearPerformanceData', 'lastYearPerformanceData', 'allReviews', 'totalReviewCount', 'averageRating', 'token', 'id'));
        }
    }
}
