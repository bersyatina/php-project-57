<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::factory()->create();
    }
    /**
     * A basic feature test example.
     */
    public function testStore(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('labels.index'));

        $response->assertOk();
        $response = $this
            ->actingAs($this->user)
            ->get(route('labels.create'));
        $response->assertOk();

        $data = Label::factory()->make()->only(['name', 'description']);

        $response = $this->actingAs($this->user)
            ->post(route('labels.store'), $data);
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', $data);
    }

    public function testUpdate(): void
    {
        $data = Label::factory()->make()->only(['name', 'description']);
        $label = Label::factory()->create();

        $response = $this->actingAs($this->user)
            ->patch(route('labels.update', $label), $data);
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', $data);
    }

    public function testEdit(): void
    {
        $label = Label::factory()->create();

        $newResponse = $this->actingAs($this->user)
            ->get(route('labels.edit', $label));
        $newResponse->assertOk();
        $this->assertDatabaseHas('labels', $label->only(['name', 'description']));
    }

    public function testDestroy(): void
    {
        $label = Label::factory()->create();

        $newResponse = $this->actingAs($this->user)
            ->delete(route('labels.destroy', $label));
        $newResponse->assertRedirect(route('labels.index'));
        $this->assertNull(Label::find($label->id));
    }
}
