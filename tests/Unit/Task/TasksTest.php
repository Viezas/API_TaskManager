<?php

namespace Tests\Unit\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TasksTest extends TestCase
{
    public function test_all_tasks()
    {
        $user = User::where('id', 1)->first();

        $response = $this->actingAs($user)->getJson('/api/tasks');

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

    public function test_all_tasks_completed()
    {
        $user = User::where('id', 1)->first();
        $task = Task::where('completed', true)->get();

        if (count($task) == 0) {
            Task::create([
                'body' => 'Test',
                'completed' => true,
                'user_id' => 1
            ]);
        }

        $response = $this->actingAs($user)->getJson('/api/tasks?completed=true');

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

    public function test_all_tasks_not_completed()
    {
        $user = User::where('id', 1)->first();
        $task = Task::where('completed', false)->get();

        if (count($task) == 0) {
            Task::create([
                'body' => 'Test',
                'user_id' => 1
            ]);
        }

        $response = $this->actingAs($user)->getJson('/api/tasks?completed=false');

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
}
