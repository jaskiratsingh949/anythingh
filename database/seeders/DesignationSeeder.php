<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Designation;

class DesignationSeeder extends Seeder
{
    public function run()
    {
        Designation::insert([
            ['name' => 'Principal'],
            ['name' => 'Clerk'],
            ['name' => 'Examiner'],
            ['name' => 'Storekeeper'],
            ['name' => 'Head office'],
            ['name' => 'Cts'],
            ['name' => 'Other'],
        ]);
    }
}
