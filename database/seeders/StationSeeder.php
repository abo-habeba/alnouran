<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Station = DB::table('Stations')->where('name', 'القطاعات')->first();
        if (!$Station) {
            DB::table('Stations')->insert([
                'name' => 'القطاعات',
                'created_at' => now(),
            ]);
        }
        $Station = DB::table('Stations')->where('name', 'جهاز الانتشار')->first();
        if (!$Station) {
            DB::table('Stations')->insert([
                'name' => 'جهاز الانتشار',
                'created_at' => now(),
            ]);
        }
        $Station = DB::table('Stations')->where('name', 'المعالجة')->first();
        if (!$Station) {
            DB::table('Stations')->insert([
                'name' => 'المعالجة',
                'created_at' => now(),
            ]);
        }
        $Station = DB::table('Stations')->where('name', 'التبخير')->first();
        if (!$Station) {
            DB::table('Stations')->insert([
                'name' => 'التبخير',
                'created_at' => now(),
            ]);
        }
    }
}
