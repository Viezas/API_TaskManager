<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

class LoginTest extends TestCase
{
    public function test_login()
    {
        $user = User::where('id', 1)->first();
        $response = $this->postJson('/api/auth/login', [
            "email" => $user['email'],
            "password" => "password"
        ]);
        
        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            $json->has('token');
        });
    }

    public function test_invalid_credentials()
    {
        $user = User::where('id', 1)->first();
        $response = $this->postJson('/api/auth/login', [
            "email" => $user['email'],
            "password" => "passaazword"
        ]);
        
        $response->assertStatus(401)->assertJson(function (AssertableJson $json) {
            $json->has('error.message');
            $json->has('error.status_code');
        });
    }

    public function test_no_input()
    {
        $response = $this->postJson('/api/auth/login', []);
        
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors.email');
            $json->has('errors.password');
        });
    }

    public function test_no_email_input()
    {
        $response = $this->postJson('/api/auth/login', [
            "password" => "passaazword"
        ]);
        
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors.email');
        });
    }

    public function test_no_password_input()
    {
        $response = $this->postJson('/api/auth/login', [
            "email" => 'test@gmail.com',
        ]);
        
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors.password');
        });
    }
}
