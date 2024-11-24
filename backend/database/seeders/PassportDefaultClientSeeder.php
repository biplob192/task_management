<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PassportDefaultClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert Personal Access Client
        $personalAccessClientId = DB::table('oauth_clients')->insertGetId([
            'name' => 'Laravel Personal Access Client',
            'secret' => Str::random(40),
            'redirect' => 'http://localhost',
            'personal_access_client' => true,
            'password_client' => false,
            'revoked' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Insert Password Grant Client
        $passwordGrantClientId = DB::table('oauth_clients')->insertGetId([
            'name' => 'Laravel Password Grant Client',
            'secret' => Str::random(40),
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => false,
            'password_client' => true,
            'revoked' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Insert into oauth_personal_access_clients table for Personal Access Client
        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $personalAccessClientId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Output client details for reference (optional)
        $this->command->info('Personal Access Client ID: ' . $personalAccessClientId);
        $this->command->info('Password Grant Client ID: ' . $passwordGrantClientId);
    }
}
