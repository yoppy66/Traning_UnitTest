<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('people')->insert([
            'name' => 'YAMADA-TARO',
            'email' => 'taro@yamada',
            'age'  => 34,
        ]);
    }
}
