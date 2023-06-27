<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaskStatus::create([
            'name' => 'новый',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        TaskStatus::create([
            'name' => 'работе',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        TaskStatus::create([
            'name' => 'на тестировании',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        TaskStatus::create([
            'name' => 'завершен',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
