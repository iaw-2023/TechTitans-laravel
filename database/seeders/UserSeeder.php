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
        $roleAdminId = DB::table('roles')->where('name', 'admin')->value('id');
        $roleEmpleadoId = DB::table('roles')->where('name', 'empleado')->value('id');

        $adminId = DB::table('users')->insertGetId([
            'name' => 'adminiaw',
            'email' => 'admin@iaw.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'remember_token' => 'abcd',
        ]);
        $empleadoTitanId = DB::table('users')->insertGetId([
            'name' => 'techtitaniaw',
            'email' => 'techtitaniaw@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'remember_token' => 'abcde',
        ]);
        $adminDanielId = DB::table('users')->insertGetId([
            'name' => 'danilo',
            'email' => 'dalaco618@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'remember_token' => 'abcdef',
        ]);

        DB::table('model_has_roles')->insert([
            ['role_id' => $roleAdminId, 'model_type' => 'App\Models\User', 'model_id' => $adminId], 
            ['role_id' => $roleEmpleadoId, 'model_type' => 'App\Models\User', 'model_id' => $empleadoTitanId],
            ['role_id' => $roleEmpleadoId, 'model_type' => 'App\Models\User', 'model_id' => $adminDanielId],
        ]);
    }
}
