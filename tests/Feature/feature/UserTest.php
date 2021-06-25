<?php

namespace Tests\Feature\feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserRegistration()
    {
        $UserFactory = new UserFactory();
        $data = $UserFactory->definition();

        $response = $this->post('api/register', $data);
        
        $response->assertStatus(201);
    }
}
