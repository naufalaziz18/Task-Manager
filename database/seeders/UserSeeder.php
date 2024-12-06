<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;  // Pastikan import User benar
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Task User',
            'email' => 'task@gmail.com',
            'password' => Hash::make('taks123'),
        ]);
    }
}
