<?php

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\Log;

class GoogleDriveSigninAction
{
    public function googleDriveSignin()
    {
        $url =  $this->formatUrlInEnconding();

        try {
            return redirect()->away($url); # Redirect to AAD signin page
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function formatUrlInEnconding()
    {
        return  config('google.url') . '?scope=' . urlencode(config('google.scope')) . '&access_type=' . config('google.access_type') . '&include_granted_scopes=true&response_type=' . config('google.response_type') . '&state=state_parameter_passthrough_value&client_id=' . config('google.client_id') . '&redirect_uri=' . urlencode(config('google.redirect_uri'));
    }
}
