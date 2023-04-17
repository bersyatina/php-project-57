<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    public User $user;
    /**
     * A basic feature test example.
     */
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::factory()->create();
    }

    public function testStore(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/task_statuses');

        $response->assertOk();
        $response = $this
            ->actingAs($this->user)
            ->get('/task_statuses/create');

        $response->assertOk();

        $response = $this->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'новая',
            ]);

        $response->assertRedirect('/task_statuses');
        $statusName = TaskStatus::orderByDesc('created_at')->first()->name ?? '';
        $this->assertSame('новая', $statusName);
    }

    public function testUpdate(): void
    {
        $statusId = TaskStatus::all()->sortByDesc('id')->first()->id ?? null;
        $newResponse = $this->actingAs($this->user)
            ->patch("/task_statuses/{$statusId}", [
                'name' => 'новая3',
            ]);

        $newResponse->assertRedirect('/task_statuses');
        $newStatusName = TaskStatus::all()->sortByDesc('id')->first()->name ?? '';

        $this->assertSame('новая3', $newStatusName);
    }

    public function testEdit(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'новая4',
            ]);
        $statusId = TaskStatus::all()->sortByDesc('id')->first()->id ?? null;
        $response->assertRedirect("/task_statuses");

        $newResponse = $this->actingAs($this->user)
            ->get("/task_statuses/{$statusId}/edit");
        $newResponse->assertOk();

        $newStatusName = TaskStatus::all()->sortByDesc('id')->first()->name ?? '';
        $this->assertSame('новая4', $newStatusName);
    }

    public function testDestroy(): void
    {
        $this->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'новая3',
            ]);
        $statusId = TaskStatus::all()->sortByDesc('id')->first()->id ?? null;

        $newResponse = $this->actingAs($this->user)
            ->delete("/task_statuses/{$statusId}");
        $newResponse->assertRedirect('/task_statuses');
        $this->assertNull(TaskStatus::find($statusId));
    }
}
