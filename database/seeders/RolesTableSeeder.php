<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert roles into the roles table
        DB::table('roles')->insert([
            [
                'role_name' => 'Super Admin',
                'description' => 'Has access to all system features and settings.',
                'photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Company Admin',
                'description' => 'Manages company-specific settings and user accounts.',
                'photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'User',
                'description' => 'Regular user with access to assigned products and features.',
                'photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
