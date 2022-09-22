<?php

namespace App\Actions;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;

class RegisterAction
{
    use ApiResponse;

    protected $data;

    public function execute(array $data)
    {
        $this->data = $data;
        return $this->createUser();
    }

    private function createUser()
    {
        $user = User::create([
            'uid' => Str::orderedUuid(),
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'password' => bcrypt($this->data['password']),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        unset($user['id']);

        return $this->success([
            'token' => $token,
            'user_details' => $user
        ], 'User created successfully', 201);
    }
}
