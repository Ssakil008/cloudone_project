<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
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
        User::create([
            'email' => 'monir.uddincloudone@gmail.com',
            'mobile' => '01622457458',
            'password' => Hash::make('monir121#'),
        ]);

        // You can add more seed data here if needed
    }
}
