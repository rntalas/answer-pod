<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalesTableSeeder extends Seeder
{
    public function run():void {
        DB::table('locales')->insert([
           ['name' => 'English', 'code' => 'en', 'image' => 'images/lang/en.png', 'created_at' => now(), 'updated_at' => now()],
           ['name' => 'Greek', 'code' => 'el', 'image' => 'images/lang/el.png', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}