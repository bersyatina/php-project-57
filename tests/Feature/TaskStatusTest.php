<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
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
        $status = TaskStatus::orderByDesc('created_at')->first();
        $this->assertSame('новая', $status->name);
    }

    public function testUpdate(): void
    {
        TaskStatus::factory()->create(['name' => 'новый']);
        TaskStatus::factory()->create(['name' => 'работе']);
        TaskStatus::factory()->create(['name' => 'на тестировании']);
        TaskStatus::factory()->create(['name' => 'завершен']);
        $status = TaskStatus::all()->sortByDesc('id')->first();
        $newResponse = $this->actingAs($this->user)
            ->patch("/task_statuses/{$status->id}", [
                'name' => 'новая3',
            ]);

        $newResponse->assertRedirect('/task_statuses');
        $newStatus = TaskStatus::all()->sortByDesc('id')->first();

        $this->assertSame('новая3', $newStatus->name);
    }

    public function testEdit(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'новая3',
            ]);
        $response->assertRedirect('/task_statuses');
        $status = TaskStatus::all()->sortByDesc('id')->first();

        $newResponse = $this->actingAs($this->user)
            ->get("/task_statuses/{$status->id}/edit");
        $newResponse->assertOk();

        $newStatus = TaskStatus::all()->sortByDesc('id')->first();
        $this->assertSame('новая3', $newStatus->name);
    }

    public function testDestroy(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'новая3',
            ]);
        $status = TaskStatus::all()->sortByDesc('id')->first();

        $newResponse = $this->actingAs($this->user)
            ->delete("/task_statuses/{$status->id}");
        $newResponse->assertRedirect('/task_statuses');
        $this->assertNull(TaskStatus::find($status->id));
    }
}
