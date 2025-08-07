<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sizes')->insert([
            ['name' => '32'],
            ['name' => '34'],
            ['name' => '36'],
            ['name' => '38'],
            ['name' => '40'],
            ['name' => '42'],
            ['name' => '44'],
        ]);
    }
}
