<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AgePolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('age_policies')->insert([
            'age_from' => 18,
            'age_to' => 25,
            'additional_cost' => 50,
        ]);
        DB::table('age_policies')->insert([
            'age_from' => 65,
            'age_to' => 150,
            'additional_cost' => 100,
        ]);
    }
}
