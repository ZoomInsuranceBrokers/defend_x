<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => 1,
            'company_id' => 0,
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'full_name' => 'Super Admin',
            'gender' => 'male',
            'photo' => null,
            'email' => 'tech@zoominsurancebrokers.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'mobile' => '9729348732',
            'dob' => '1980-01-01',
            'created_on' => now(),
            'created_by' => null,
            'is_active' => true,
            'is_delete' => false,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
