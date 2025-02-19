<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Fee App', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Payroll', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Attendance', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Payment Gateway', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Salary', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Concession', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HR Module', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Result', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}