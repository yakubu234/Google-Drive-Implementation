<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\GoogleDriveSignInController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [AuthenticationController::class, 'register']);
Route::post('signin', [AuthenticationController::class, 'signin']);

Route::group(['middleware' => ['auth:sanctum', 'blacklist']], function () {

    Route::get('refreshToken', [GoogleDriveSignInController::class, 'refreshGoogleAccessToken']);

    Route::post('upload-file', [GoogleDriveSignInController::class, 'sampleUpload']);
    Route::get('list-file', [GoogleDriveSignInController::class, 'driveList']);

    Route::get('sign-out', [AuthenticationController::class, 'logout']);
    Route::get('getDriveResponse', [GoogleDriveSignInController::class, 'getCallbackDetails']);
    Route::get('getDriveResponseAuth', [GoogleDriveSignInController::class, 'getDriveResponseAuth']);
});
