<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PageKiteApiController extends Controller
{

    public $apiToken;

    public function __construct()
    {
        $this->apiToken = 'cnbwe9y8f3fj893hefncwe8fednecibqwy8f';
    }
    public function getAccessToken()
    {
        $url = "https://app.blackkitetech.com/api/v2/oauth/token";

        $client_id = "569b2c06bd0847ce8b14cd825d2a2ebe";
        $client_secret = "fc4935826234479cafc648e813482834";

        $data = [
            "grant_type" => "client_credentials",
            "client_id" => $client_id,
            "client_secret" => $client_secret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return "Curl Error: " . curl_error($ch);
        }

        curl_close($ch);

        $response_data = json_decode($response, true);

        if (isset($response_data['access_token'])) {
            return $response_data['access_token'];
        } else {
            return "Error: Unable to retrieve access token";
        }
    }

    public function getCompanies(Request $request)
    {
        if ($this->apiToken != $request->api_key) {
            return response()->json([
                'Message' => 'You are not authorized.',
            ], 401);
        }

        $url = "https://app.blackkitetech.com/api/v2/companies?page_number=1&page_size=10000";
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token"
        ])->get($url);

        if ($response->status() === 401) {
            return response()->json([
                'Message' => 'Unauthorized access. Please check your credentials.',
            ], 401);
        }

        if ($response->status() === 403) {
            return response()->json([
                'Message' => 'Forbidden access. You do not have permission.',
            ], 403);
        }

        if ($response->status() === 404) {
            return response()->json([
                'Message' => 'API endpoint not found.',
            ], 404);
        }

        if ($response->failed()) {
            return response()->json([
                'Message' => 'An unexpected error occurred.',
                'status' => $response->status()
            ], $response->status());
        }

        return response()->json([
            'status' => true,
            'data' => $response->json(),
        ], 200);
    }

    public function companySummary(Request $request)
    {
        if (empty($request->cmp_id)) {
            return response()->json([
                'Message' => 'Invalid company ID.',
            ], 400);
        }


        if ($this->apiToken != $request->api_key) {
            return response()->json([
                'Message' => 'You are not authorized.',
            ], 401);
        }

        $url = "https://app.blackkitetech.com/api/v2/companies/{$request->cmp_id}/summary";
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token"
        ])->get($url);

        if ($response->status() === 401) {
            return response()->json([
                'Message' => 'You are not authorized.',
            ], 401);
        }

        if ($response->status() === 404) {
            return response()->json([
                'Message' => 'The search key not found.',
            ], 404);
        }

        if ($response->failed()) {
            return response()->json([
                'Message' => 'A generic failure occurred.',
            ], $response->status());
        }

        return response()->json([
            'status' => true,
            'data' => $response->json(),
        ], 200);
    }

    public function getCompanyReport(Request $request)
    {
        if (empty($request->cmp_id) || !in_array($request->report_type, ['summary', 'strategy', 'rsi'])) {
            return response()->json([
                'Message' => 'Invalid company ID or report type.',
            ], 400);
        }

        if ($this->apiToken != $request->api_key) {
            return response()->json([
                'Message' => 'You are not authorized.',
            ], 401);
        }

        $url = "https://app.blackkitetech.com/api/v2/companies/{$request->cmp_id}/report/{$request->report_type}";
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token"
        ])->get($url);

        if ($response->status() === 401) {
            return response()->json([
                'Message' => 'You are not authorized.',
            ], 401);
        }

        if ($response->status() === 404) {
            return response()->json([
                'Message' => 'The search key not found.',
            ], 404);
        }

        if ($response->failed()) {
            return response()->json([
                'Message' => 'A generic failure occurred.',
            ], $response->status());
        }

        return response()->json([
            'status' => true,
            'data' => $response->json(),
        ], 200);
    }

    public function getCompanyDataBreach(Request $request)
    {
        $request->validate([
            'cmp_id' => 'required|numeric',
            'api_key' => 'required|string'
        ]);

        if ($this->apiToken != $request->api_key) {
            return response()->json([
                'Message' => 'You are not authorized.',
            ], 401);
        }

        $pageNumber = 1;
        $pageSize = 10000000;
        $url = "https://app.blackkitetech.com/api/v2/companies/{$request->cmp_id}/findings/databreach?page_number={$pageNumber}&page_size={$pageSize}";

        $token = $this->getAccessToken();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            "Authorization: Bearer $token"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            curl_close($ch);
            return response()->json([
                'Message' => 'Curl Error: ' . curl_error($ch),
            ], 500);
        }

        curl_close($ch);

        if ($httpCode === 401) {
            return response()->json([
                'Message' => 'You are not authorized.',
            ], 401);
        }

        if ($httpCode === 404) {
            return response()->json([
                'Message' => 'The search key not found.',
            ], 404);
        }

        if ($httpCode !== 200) {
            return response()->json([
                'Message' => 'A generic failure occurred.',
            ], $httpCode);
        }

        $data = json_decode($response, true);

        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }
}
