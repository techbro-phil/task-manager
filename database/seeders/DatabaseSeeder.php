<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demoUser = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        $work = Category::factory()->for($demoUser)->create(['name' => 'Work']);
        $personal = Category::factory()->for($demoUser)->create(['name' => 'Personal']);
        $errands = Category::factory()->for($demoUser)->create(['name' => 'Errands']);

        $tasks = [
            ['title' => 'Draft the Q3 project proposal', 'category_id' => $work->id, 'is_completed' => false],
            ['title' => 'Review pull requests from the team', 'category_id' => $work->id, 'is_completed' => true],
            ['title' => 'Prepare slides for Monday standup', 'category_id' => $work->id, 'is_completed' => false],
            ['title' => 'Call the dentist to reschedule', 'category_id' => $personal->id, 'is_completed' => false],
            ['title' => 'Read one chapter before bed', 'category_id' => $personal->id, 'is_completed' => true],
            ['title' => 'Pick up dry cleaning', 'category_id' => $errands->id, 'is_completed' => false],
            ['title' => 'Buy groceries for the week', 'category_id' => $errands->id, 'is_completed' => false],
            ['title' => 'Water the plants', 'category_id' => null, 'is_completed' => true],
        ];

        foreach ($tasks as $task) {
            Task::factory()->for($demoUser)->create($task);
        }
    }
}