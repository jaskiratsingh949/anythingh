<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class SchoolsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('schools')->insert([
            ['name' => 'Bilga', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ajitsar ratia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bhadaur', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ghugg', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
