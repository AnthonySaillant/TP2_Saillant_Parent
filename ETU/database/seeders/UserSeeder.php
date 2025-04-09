<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use database\factories\UserFactory;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'login' => 'Admin',
            'password' => bcrypt('123'),
            'role_id' => 2,
        ]);
    }
}
