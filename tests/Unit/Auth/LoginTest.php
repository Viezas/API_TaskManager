<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

class LoginTest extends TestCase
{
    public function test_valid_login()
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
}
