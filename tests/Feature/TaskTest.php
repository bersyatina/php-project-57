<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Faker\Guesser\Name;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public User $user;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::factory()->create();
    }

    public function testStore(): void
    {
        $response = $this->actingAs($this->user)->get('/tasks');
        $response->assertOk();

        $response = $this->actingAs($this->user)->get('/tasks/create');
        $response->assertOk();

        $this->actingAs($this->user)->post('/task_statuses', [
            'name' => 'новая17',
        ]);
        $status = TaskStatus::orderByDesc('created_at')->first();

        $response = $this->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Первая задача',
                'description' => 'Описание первой задачи',
                'status_id' => $status->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
            ]);
        $response->assertRedirect('/tasks');
        $task = Task::orderByDesc('created_at')->first();
        $this->assertSame('Первая задача', $task->name);
        $this->assertSame('Описание первой задачи', $task->description);
        $this->assertSame($status->id, $task->status_id);
        $this->assertSame($this->user->id, $task->created_by_id);
        $this->assertSame($this->user->id, $task->assigned_to_id);
    }

    public function testUpdate(): void
    {
        $this->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'тестовая',
            ]);
        $status = TaskStatus::all()->sortByDesc('id')->first();

        $response = $this->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'тестовая задача',
                'description' => 'Описание обновленной задачи',
                'status_id' => $status->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
            ]);
        $response->assertRedirect('/tasks');
        $task = Task::all()->sortByDesc('id')->first();

        $this->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'новая2',
            ]);
        $newStatus = TaskStatus::all()->sortByDesc('id')->first();

        $newResponse = $this->actingAs($this->user)
            ->patch("/tasks/{$task->id}", [
                'name' => 'Измененная',
                'description' => 'Описание измененной задачи',
                'status_id' => $newStatus->id,
            ]);

        $newResponse->assertRedirect('/tasks');
        $newTask = Task::all()->sortByDesc('id')->first();

        $this->assertSame('Измененная', $newTask->name);
        $this->assertSame('Описание измененной задачи', $newTask->description);
        $this->assertSame($newStatus->id, $newTask->status_id);
    }

    public function testEdit(): void
    {
        $status = TaskStatus::orderByDesc('id')->limit(1)->first();
        $response = $this->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'сделать выгрузку',
                'description' => 'Выгрузка из Excel',
                'status_id' => $status->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
            ]);

        $response->assertRedirect('/tasks');
        $task = Task::all()->sortByDesc('id')->first();

        $newResponse = $this->actingAs($this->user)
            ->get("/tasks/{$task->id}/edit");
        $newResponse->assertOk();

        $newTask = Task::all()->sortByDesc('id')->first();
        $this->assertSame('сделать выгрузку', $newTask->name);
    }

    public function testDestroy(): void
    {
        $status = TaskStatus::orderByDesc('id')->limit(1)->first();

        $response = $this->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'Задача ' . time(),
                'description' => 'Выгрузка из Excel',
                'status_id' => $status->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
            ]);
        $task = Task::all()->sortByDesc('id')->first();

        $newResponse = $this->actingAs($this->user)
            ->delete("/tasks/{$task->id}");
        $newResponse->assertRedirect('/tasks');
        $newTask = Task::find($task->id);
        $this->assertNull($newTask);
    }

    public function testShow(): void
    {
        $status = TaskStatus::orderByDesc('id')->limit(1)->first();

        $this->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'сделать выгрузку2',
                'description' => 'Выгрузка из Excel, pdf',
                'status_id' => $status->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
            ]);
        $task = Task::all()->sortByDesc('id')->first();
        $response = $this->actingAs($this->user)->get("/tasks/{$task->id}");
        $this->assertTrue(str_contains($response->getContent(), 'Выгрузка из Excel, pdf'));
        $this->assertTrue(str_contains($response->getContent(), 'сделать выгрузку2'));
    }
}
