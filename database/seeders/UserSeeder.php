<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'name' => 'Wendy',
            'email' => 'wendy@example.com',
            'username' => 'wendy',
            'password' => bcrypt('wendy123'),
        ]);

        User::create([
            'name' => 'Leo',
            'email' => 'leo@example.com',
            'username' => 'leo',
            'password' => bcrypt('leo123'),
        ]);

        User::create([
            'name' => 'Kike',
            'email' => 'kike@example.com',
            'username' => 'kike',
            'password' => bcrypt('kike123'),
        ]);

        User::create([
            'name' => 'jose',
            'email' => 'jose@example.com',
            'username' => 'jose',
            'password' => bcrypt('jose123'),
        ]);
    }
}
