<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Username',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('user'),
            'mobile' => '0962334135',
            'gender' => 1,
            'address' => 'Chiến Thắng, An Lão, Hải Phòng',
        ]);

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permission
        DB::table('permissions')->insert([
            // Account
            ['name' => 'acc.add', 'guard_name' => 'web'],
            ['name' => 'acc.edit', 'guard_name' => 'web'],
            ['name' => 'acc.delete', 'guard_name' => 'web'],
            ['name' => 'acc.view', 'guard_name' => 'web'],
            ['name' => 'acc.confirm', 'guard_name' => 'web'],

            // Invoice
            ['name' => 'inv.add', 'guard_name' => 'web'],
            ['name' => 'inv.edit', 'guard_name' => 'web'],
            ['name' => 'inv.delete', 'guard_name' => 'web'],
            ['name' => 'inv.view', 'guard_name' => 'web'],
            ['name' => 'inv.confirm', 'guard_name' => 'web'],

            // Log system
            ['name' => 'log.delete', 'guard_name' => 'web'],
            ['name' => 'log.view', 'guard_name' => 'web'],

            /* Add here */
        ]);

        // Role
        $roleAdmin = Role::create(['name' => 'Administrator', 'guard_name' => 'web']);

        // Give permission
        $roleAdmin->givePermissionTo(Permission::all());

        // Assign role
        $admin->assignRole($roleAdmin);
    }
}
