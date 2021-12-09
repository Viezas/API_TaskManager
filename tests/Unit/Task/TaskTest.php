<?php

namespace Tests\Unit\Task;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_one_task()
    {
        $user = User::where('id', 1)->first();
        $task = Task::where('user_id', 1)->first();

        $response = $this->actingAs($user)->getJson('/api/tasks/'.$task['id']);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    "body",
                    "completed",
                    "created_at",
                    "updated_at"
                ]
            ],
        ]);
    }

    public function test_unknown_task()
    {
        $user = User::where('id', 1)->first();
        $task = Task::where('user_id', '!=', 1)->first();
        
        $response = $this->actingAs($user)->getJson('/api/tasks/'.$task['id']);

        $response->assertStatus(404)->assertJsonStructure([
            'error' => [
                'message',
                'status_code'
            ],
        ]);
    }

    public function test_create_task()
    {
        $user = User::where('id', 1)->first();

        $response = $this->actingAs($user)->postJson('/api/tasks', [
            "body" => "test",
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'message'
        ]);
    }

    public function test_create_task_no_input()
    {
        $user = User::where('id', 1)->first();

        $response = $this->actingAs($user)->postJson('/api/tasks', []);

        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors' => [
                'body'
            ]
        ]);
    }

    public function test_update_task()
    {
        $user = User::where('id', 1)->first();
        $task = Task::where('user_id', 1)->first();

        $response = $this->actingAs($user)->putJson('/api/tasks/'.$task['id'], [
            "body" => "test"
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'message'
        ]);
    }

    public function test_update_unknown_task()
    {
        $user = User::where('id', 1)->first();
        $task = Task::where('user_id', '!=', 1)->first();
        
        $response = $this->actingAs($user)->putJson('/api/tasks/'.$task['id'], [
            "body" => "test"
        ]);

        $response->assertStatus(404)->assertJsonStructure([
            'error' => [
                'message',
                'status_code'
            ],
        ]);
    }

    public function test_delete_task()
    {
        $user = User::where('id', 1)->first();
        $task = Task::where('user_id', 1)->first();

        $response = $this->actingAs($user)->deleteJson('/api/tasks/'.$task['id']);

        $response->assertStatus(204);
    }

    public function test_delete_unknown_task()
    {
        $user = User::where('id', 1)->first();
        $task = Task::where('user_id', '!=', 1)->first();

        $response = $this->actingAs($user)->deleteJson('/api/tasks/'.$task['id']);

        $response->assertStatus(404)->assertJsonStructure([
            'error' => [
                'message',
                'status_code'
            ],
        ]);
    }
}
