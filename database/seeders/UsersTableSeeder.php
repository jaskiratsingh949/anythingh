<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            ['name' => 'Mandeep', 'email' => 'mandeep@example.com', 'role' => 'ERP Team', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Simar', 'email' => 'simar@example.com', 'role' => 'ERP Team', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kuldeep', 'email' => 'kuldeep@example.com', 'role' => 'ERP Team', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ankit', 'email' => 'ankit@example.com', 'role' => 'ERP Team', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Karam', 'email' => 'karam@example.com', 'role' => 'ERP Team', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Arun', 'email' => 'arun@example.com', 'role' => 'Developer Team', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}