<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Walker',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345678'), 
                'email_verified_at' => now(),
            ],
            [
                'name' => 'John Doe',
                'email' => 'user@gmail.com',
                'password' => bcrypt('12345678'), 
                'email_verified_at' => now(),
            ]
        ]); 
    }
}
