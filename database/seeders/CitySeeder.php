<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::create([
            'name' => 'جده',
            'name_en' => 'jeddh',
            'code' => '00966',
            'region_id'=>1
        ]);
        City::create([
            'name' => 'الرياض',
            'name_en' => 'Riyadh',
            'code' => '00966',
            'region_id'=>1
        ]);


    }
}
