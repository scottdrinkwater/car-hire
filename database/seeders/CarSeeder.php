<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('local')) {        
            DB::table('cars')->insert([
                'model' => 'Peugeot',
                'cost' => 10,
                'registration' => 'PE96 UGE',
            ]);
            DB::table('cars')->insert([
                'model' => 'Fiat',
                'cost' => 70,
                'registration' => 'FI57 ATT',
            ]);
            DB::table('cars')->insert([
                'model' => 'Porsche',
                'cost' => 500,
                'registration' => 'PO21 RSC',
            ]);
        }
    }
}
