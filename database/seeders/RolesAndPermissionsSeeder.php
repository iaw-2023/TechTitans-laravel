<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'crear turnos']);
        Permission::create(['name' => 'eliminar turnos']);
        Permission::create(['name' => 'editar turnos']);

        Permission::create(['name' => 'crear canchas']);
        Permission::create(['name' => 'eliminar canchas']);
        Permission::create(['name' => 'editar canchas']);

        Permission::create(['name' => 'crear categorias']);
        Permission::create(['name' => 'eliminar categorias']);
        Permission::create(['name' => 'editar categorias']);        
         
        //crear roles
        $roleAdmin = Role::create(['name'=>'admin']);
        $roleEmpleado = Role::create(['name'=>'empleado']);

        $roleAdmin->givePermissionTo(Permission::all());

        $roleEmpleado->givePermissionTo('crear turnos', 'eliminar turnos', 'editar turnos');                                
    }
}