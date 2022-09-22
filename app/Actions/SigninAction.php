<?php

namespace App\Actions;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class SigninAction
{
    use ApiResponse;

    public function login(array $data)
    {
        if (!Auth::attempt($data)) {
            return $this->error('Credentials do not match our records', 401);
        }


        $user = auth()->user();
        $token = $user->createToken('API Token')->plainTextToken;
        unset($user['id']);

        return $this->success([
            'token' => $token,
            'user' => $user,
        ], 'login successful');
    }
}
