<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder 
{
    public function run(): void 
    {
        User::create(['name'=>'System Admin', 'email'=>'admin@example.com', 'password'=>Hash::make('password'), 'role'=>'admin']);
        User::create(['name'=>'Lab Staff', 'email'=>'staff@example.com', 'password'=>Hash::make('password'), 'role'=>'staff']);
        User::create(['name'=>'Juan Dela Cruz', 'email'=>'student@example.com', 'password'=>Hash::make('password'), 'role'=>'student']);
    }
}