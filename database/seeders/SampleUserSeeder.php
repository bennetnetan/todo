<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Only seed todos the first time to avoid duplicates across runs
        if (! Todo::where('title', 'Welcome to the demo')->exists()) {
            Todo::insert([
                [
                    'title' => 'Welcome to the demo',
                    'description' => 'Feel free to add, edit, complete, or delete tasks.',
                    'is_completed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Mark me done',
                    'description' => 'Use the Complete button to toggle status.',
                    'is_completed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Edit this task',
                    'description' => 'Try editing the title or description.',
                    'is_completed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
