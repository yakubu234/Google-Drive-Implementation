<?php

namespace App\Actions;

use App\Models\GoogleDrive;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshGoogleDriveTokenAction
{
    use ApiResponse;

    public function __construct()
    {
        $this->GoogleDrive = GoogleDrive::first();

        if (empty($this->GoogleDrive->refresh_token)) return $this->success('Refresh Token not available');

        return $this->refreshGoogleDriveTokenAction();
    }

    public function refreshGoogleDriveTokenAction()
    {
        $response = Http::asForm()->withHeaders([
            "Content-Type" => "application/x-www-form-urlencoded"
        ])->post("https://www.googleapis.com/oauth2/v4/token", [
            "client_id" =>  config('google.client_id'),
            "client_secret" => "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",
            "refresh_token" => $this->GoogleDrive->refresh_token,
            "grant_type" => "refresh_token"
        ])->json();


        if (strlen($response['access_token']) < 12) return $this->error('Refresh Token is incorrect', 402);

        GoogleDrive::updateOrCreate(
            ['refresh_token' => $this->GoogleDrive->refresh_token],
            ['access_token' => $response['access_token']]
        );

        $this->success('Access Token refreshed');
    }
}
