<?php

use App\Http\Controllers\AuthController;
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

Route::post('/register', [AuthController::class, 'register'])->name('registration');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('fetch-user/{user_id}',[AuthController::class,'getUserDetails'])->name('selectUser');
    Route::put('update-user',[AuthController::class,'updateUserDetails'])->name('updateUser');
    Route::delete('delete-user/{user_id}',[AuthController::class,'deleteUserDetails'])->name('deleteUser');

});
