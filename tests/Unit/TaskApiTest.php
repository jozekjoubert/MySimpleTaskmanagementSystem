<?php

use Tests\TestCase;

class TaskApiTest extends TestCase
{
    public function test_get_all_tasks()
    {
        $response = $this->getJson('/api/httpTasks');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id', 'title', 'description', 'status', 'user_id', 'due_date'
                     ]
                 ]);
    }
}
