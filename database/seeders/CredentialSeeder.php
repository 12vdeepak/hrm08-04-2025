<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@hrm.com',
            'password' => Hash::make('password123'),
            'role_id'=>1,
        ]);
        
        DB::table('users')->insert([
            'name' => 'HR',
            'email' => 'hr@hrm.com',
            'password' => Hash::make('password123'),
            'role_id'=>2,
            'employee_status'=>"Active"
        ]);

        // DB::table('users')->insert([
        //     'name' => 'User',
        //     'email' => 'user@hrm.com',
        //     'password' => Hash::make('password123'),
        //     'role_id'=>3,
        // ]);
    }
}
