<?php

namespace App\Actions;

use Exception;
use App\Models\GoogleDrive;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleDriveCallbackAction
{
    use ApiResponse;

    public function googleDriveCallback(string $code)
    {
        $response = Http::asForm()->withHeaders([
            "Content-Type" => "application/x-www-form-urlencoded"
        ])->post("https://www.googleapis.com/oauth2/v4/token", [
            "code" => $code,
            "client_id" =>  config('google.client_id'),
            "client_secret" => "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",
            "grant_type" => "authorization_code",
            "redirect_uri" => config('google.redirect_uri'),
        ])->json();

        if (strlen($response['access_token']) < 12 || strlen($response['refresh_token']) < 12) {
            return $this->error('The Callback Query Parameter is incorrect', 402);
        }

        GoogleDrive::truncate()->create([
            'access_token' => $response['access_token'],
            'refresh_token' => $response['refresh_token']
        ]);

        return $this->success('token has been generated.');
    }
}
