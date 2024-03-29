<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Database\Factories\TaskStatusFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

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
            ->get(route('task_statuses.index'));
        $response->assertOk();

        $response = $this
            ->actingAs($this->user)
            ->get(route('task_statuses.create'));
        $response->assertOk();

        $data = TaskStatus::factory()->make()->only(['name']);
        $response = $this->actingAs($this->user)
            ->post(route('task_statuses.store'), $data);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', $data);
    }

    public function testUpdate(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $data = TaskStatus::factory()->make()->only(['name']);
        $newResponse = $this->actingAs($this->user)
            ->patch(route('task_statuses.update', $taskStatus), $data);

        $newResponse->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', $data);
    }

    public function testEdit(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $newResponse = $this->actingAs($this->user)
            ->get(route("task_statuses.edit", $taskStatus));
        $newResponse->assertOk();
        $this->assertDatabaseHas('task_statuses', $taskStatus->only(['name']));
    }

    public function testDestroy(): void
    {
        $taskStatus = TaskStatus::factory()->create()->toArray();
        $newResponse = $this->actingAs($this->user)
            ->delete(route('task_statuses.destroy', $taskStatus['id']));
        $newResponse->assertRedirect(route('task_statuses.index'));
        $this->assertNull(TaskStatus::find($taskStatus['id']));
    }
}
