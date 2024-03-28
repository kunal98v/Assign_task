<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('data_types')->insert([
            array(
                "data_type_name" => "numeric",
                "comments"=>"checks if numeric",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
                
            ),

           
        ]);
    }
}

