<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $statuses = TaskStatus::all()->pluck('id')->toArray();
        $randomIdStatus = array_rand($statuses);

        $users = User::all()->pluck('id')->toArray();

        $randomIdAssigned = array_rand($users);

        $randomIdCreater = array_rand($users);

        return [
            'status_id' => $statuses[$randomIdStatus],
            'name' => $this->faker->unique()->name(),
            'description' => $this->faker->unique()->text(100),
            'created_by_id' => $randomIdCreater,
            'assigned_to_id' => $randomIdAssigned,
        ];
    }
}
