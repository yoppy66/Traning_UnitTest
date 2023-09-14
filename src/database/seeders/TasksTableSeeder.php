<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
                [
                    'name'=> 'Task1',
                ],
                [
                    'name'=> 'Task2',
                ],
                [
                    'name'=> 'Task3',
                ]
        ]

        );
    }
}
