<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'name' => 'Super Admin',
        ]);
        DB::table('roles')->insert([
            'name' => 'HR',
        ]);
        DB::table('roles')->insert([
            'name' => 'Employee',
        ]);
        DB::table('roles')->insert([
            'name' => 'Reporting Manager',
        ]);
    }
}
