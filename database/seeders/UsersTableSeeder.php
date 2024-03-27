<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed the super admin user
        // User::create([
        //     'email' => 'monir.uddincloudone@gmail.com',
        //     'mobile' => '01622457458',
        //     'password' => Hash::make('monir121#'),
        // ]);

        UserRole::create([
            'user_id' => 1,
            'role_id' => 1,
        ]);

        // Permission::create([
        //     'role_id' => 1,
        //     'menu_id' => 1,
        //     'read' => 'yes',
        //     'create' => 'yes',
        //     'edit' => 'yes',
        //     'delete' => 'yes',
        // ]);

        // Permission::create([
        //     'role_id' => 1,
        //     'menu_id' => 2,
        //     'read' => 'yes',
        //     'create' => 'yes',
        //     'edit' => 'yes',
        //     'delete' => 'yes',
        // ]);

        // Permission::create([
        //     'role_id' => 1,
        //     'menu_id' => 3,
        //     'read' => 'yes',
        //     'create' => 'yes',
        //     'edit' => 'yes',
        //     'delete' => 'yes',
        // ]);

        // You can add more seed data here if needed
    }
}
