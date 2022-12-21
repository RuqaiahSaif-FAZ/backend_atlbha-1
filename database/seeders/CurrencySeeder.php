<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create([
            'name' => 'الريال السعودي',
            'name_en' => 'RS',
            'image' => 'd.png',
            
        ]);
        Currency::create([
            'name' => 'الدولار',
            'name_en' => '$',
            'image' => 'd.png',

            
        ]);
    }
}