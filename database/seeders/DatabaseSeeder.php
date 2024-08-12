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

        User::factory()->create([
            'name' => 'Shoaib Rehan',
            'email' => 'shoaib@example.com',
        ]);
        User::factory()->create([
            'name' => 'Abdul Moeez',
            'email' => 'moeez@example.com',
        ]);
        User::factory()->create([
            'name' => 'Feroz Rajpoot',
            'email' => 'feroz@example.com',
        ]);
    }
}
