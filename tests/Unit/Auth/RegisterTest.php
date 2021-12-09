<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_successful_register()
    {
        $user = User::where('email', 'test@gmail.com')->get();
        if(count($user) > 0) {
            User::where('email', 'test@gmail.com')->delete();
        }

        $response = $this->postJson('/api/auth/register', [
            "name" => "test",
            "email" => "test@gmail.com",
            "password" => "password"
        ]);
        
        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            $json->has('token');
        });
    }

    public function test_no_input()
    {
        $response = $this->postJson('/api/auth/register', []);
        
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors.name');
            $json->has('errors.email');
            $json->has('errors.password');
        });
    }

    public function test_no_name_input()
    {
        $response = $this->postJson('/api/auth/register', [
            "email" => "test@gmail.com",
            "password" => "password"
        ]);
        
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors.name');
        });
    }

    public function test_no_email_input()
    {
        $response = $this->postJson('/api/auth/register', [
            "name" => "test",
            "password" => "password"
        ]);
        
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors.email');
        });
    }

    public function test_no_password_input()
    {
        $response = $this->postJson('/api/auth/register', [
            "name" => "test",
            "email" => "test@gmail.com",
        ]);
        
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors.password');
        });
    }

    public function test_email_already_exist()
    {
        $response = $this->postJson('/api/auth/register', [
            "name" => "test",
            "email" => "test@gmail.com",
            "password" => "password"
        ]);
        
        $response->assertStatus(422)->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->has('errors.email');
        });
    }
}
