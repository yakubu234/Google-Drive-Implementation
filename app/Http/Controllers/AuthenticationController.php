<?php

namespace App\Http\Controllers;

use App\Actions\RegisterAction;
use App\Actions\SigninAction;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        return (new RegisterAction())->execute($request->validated());
    }

    public function signin(LoginRequest $request)
    {
        return (new SigninAction())->login($request->validated());
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
