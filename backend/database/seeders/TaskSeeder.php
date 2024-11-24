<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['to_do', 'in_progress', 'in_review', 'complete'];

        for ($i = 0; $i < 30; $i++) {
            // Generate a start date
            // $startDate = fake()->date();

            // Generate a start date that is in the future (from today)
            $startDate = fake()->dateTimeBetween('now', '+7 days')->format('Y-m-d');

            // Generate a due date that is after the start date
            $dueDate = (new \DateTime($startDate))->modify('+3 days')->format('Y-m-d');

            $data[] = [
                'title'         => fake()->sentences(1, true),
                'description'   => fake()->paragraph(),
                'status'        => $statuses[array_rand($statuses)],
                'assigned_to'   => fake()->numberBetween(0, 100),
                'start_date'    => $startDate,
                'due_date'      => $dueDate,
                'created_by'    => fake()->numberBetween(1, 10),
                'created_at'    => now(),
            ];
        }

        $chunks = array_chunk($data, 10);
        foreach ($chunks as $chunk) {
            Task::insert($chunk);
        }
    }
}
