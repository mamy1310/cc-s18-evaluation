<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $manageNotes = Permission::create(['name' => 'manage notes']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo($manageNotes);
    }
}
