<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Run the following command menually if needed
        // php artisan db:seed --class=PassportDefaultClientSeeder
        // php artisan db:seed --class=TaskSeeder


        $this->call([
            RolesAndPermissionsSeeder::class,
            PassportDefaultClientSeeder::class,
            UserSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
