<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'name' => $faker->company,
                'location' => $faker->address,
                'website' => $faker->url,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('factories')->insert($data);
    }
}
