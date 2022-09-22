<?php
// Copyright (c) Microsoft Corporation.
// Licensed under the MIT License.

// Access environment through the config helper
// This will avoid issues when using Laravel's config caching
// https://laravel.com/docs/8.x/configuration#configuration-caching
return [
    'url'             => env('google_auth_url', ''),
    'client_id'             => env('client_id', ''),
    'access_type'         => env('access_type', ''),
    'redirect_uri'       => env('redirect_uri', ''),
    'scope'            => env('scope', ''),
    'response_type'         => env('response_type'),
    'client_secret'         => env('client_secret'),

];
