<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = DB::table('users')
            ->where('email', 'admin@admin.com')
            ->first();

        if (!$user) {
            DB::table('users')->insert([
                'name' => 'admin',
                'phone' => '01234567891',
                'Job_title' => 'engeneer',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
            ]);
        }
    }
}
