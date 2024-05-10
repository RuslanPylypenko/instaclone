<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'nick' => 'agent007',
            'email' => 'test@example.com',
        ]);

        User::factory(100)->create();
    }
}
