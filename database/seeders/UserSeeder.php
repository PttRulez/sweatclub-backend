<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'nickname' => 'PttRulez',
            'role' => 'admin',
            'password' => Hash::make('654321'),
        ]);

        DB::table('users')->insert([
            'nickname' => 'Fedya',
            'role' => null,
            'password' => Hash::make('654321'),
        ]);
    }
}
