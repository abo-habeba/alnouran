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
                'roles' => 'admin',
                'created_at' => now(),
                'Job_title' => 'engeneer',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
            ]);
        }
        $rest = DB::table('rest_balances')->where('user_id', 1)->first();
        if (!$rest) {
            DB::table('rest_balances')->insert([
                'balance' => 0,
                'user_id' => 1,
                'created_at' => now(),
            ]);
        }
        $regular = DB::table('regular_balances')->where('user_id', 1)->first();
        if (!$regular) {
            DB::table('regular_balances')->insert([
                'balance' => 0,
                'user_id' => 1,
                'created_at' => now(),
            ]);
        }
    }
}
