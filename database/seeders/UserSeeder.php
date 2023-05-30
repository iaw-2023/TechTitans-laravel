<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'adminiaw',
            'email' => 'admin@iaw.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'remember_token' => 'abcd',

        ]);
        DB::table('users')->insert([
            'name' => 'techtitaniaw',
            'email' => 'techtitaniaw@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'remember_token' => 'abcde',

        ]);
        DB::table('users')->insert([
            'name' => 'danilo',
            'email' => 'dalaco618@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'remember_token' => 'abcdef',

        ]);
    }
}
