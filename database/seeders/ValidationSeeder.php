<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValidationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('validations')->insert([
          
            array(
                "validation_name" => "email",
                "comments"=>"checks if email ",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
                
            ),
            array(
                "validation_name" => "phone",
                "comments"=>"checks if phone ",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now(),
                
            ),
        ]);
    }
}
