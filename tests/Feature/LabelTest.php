<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
{
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
            ->get('/labels');

        $response->assertOk();
        $response = $this
            ->actingAs($this->user)
            ->get('/labels/create');

        $response->assertOk();

        $response = $this->actingAs($this->user)
            ->post('/labels', [
                'name' => 'новая',
                'description' => 'описание новой метки',
            ]);
        $response->assertRedirect('/labels');
        $label = Label::orderByDesc('created_at')->first();
        $this->assertSame('новая', $label->name);
        $this->assertSame('описание новой метки', $label->description);
    }

    public function testUpdate(): void
    {
        $this->actingAs($this->user)
            ->post('/labels', [
                'name' => 'новая2',
                'description' => 'Описание новаой метки 2',
            ]);
        $label = Label::all()->sortByDesc('id')->first();
        $newResponse = $this->actingAs($this->user)
            ->patch("/labels/{$label->id}", [
                'name' => 'новая3',
                'description' => 'Описание новаой метки 3',
            ]);

        $newResponse->assertRedirect('/labels');
        $newLabel = Label::all()->sortByDesc('id')->first();

        $this->assertSame('новая3', $newLabel->name);
        $this->assertSame('Описание новаой метки 3', $newLabel->description);
    }

    public function testEdit(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/labels', [
                'name' => 'новая4',
                'description' => 'Описание новаой метки 4',
            ]);
        $response->assertRedirect('/labels');
        $label = Label::all()->sortByDesc('id')->first();

        $newResponse = $this->actingAs($this->user)
            ->get("/labels/{$label->id}/edit");
        $newResponse->assertOk();

        $newLabel = Label::all()->sortByDesc('id')->first();
        $this->assertSame('новая4', $newLabel->name);
        $this->assertSame('Описание новаой метки 4', $newLabel->description);
    }

    public function testDestroy(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/labels', [
                'name' => 'новая5',
                'description' => 'Описание новаой метки 5',
            ]);
        $label = Label::all()->sortByDesc('id')->first();

        $newResponse = $this->actingAs($this->user)
            ->delete("/labels/{$label->id}");
        $newResponse->assertRedirect('/labels');
        $this->assertNull(Label::find($label->id));
    }
}