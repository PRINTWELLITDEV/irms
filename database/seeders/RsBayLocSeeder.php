<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RsBayLocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rsbayloc')->insert([
            ['rssite' => 'PI-SP', 'rsbaynum' => 'A1', 'createdate' => Carbon::now(),'createdby' => 'sa',],
            ['rssite' => 'PI-SP', 'rsbaynum' => 'A2', 'createdate' => Carbon::now(),'createdby' => 'sa',],
            ['rssite' => 'PI-SP', 'rsbaynum' => 'A3', 'createdate' => Carbon::now(),'createdby' => 'sa',],
            ['rssite' => 'FP-SP', 'rsbaynum' => 'A1', 'createdate' => Carbon::now(),'createdby' => 'sa',],
            ['rssite' => 'FP-SP', 'rsbaynum' => 'A2', 'createdate' => Carbon::now(),'createdby' => 'sa',],
            ['rssite' => 'FP-SP', 'rsbaynum' => 'A3', 'createdate' => Carbon::now(),'createdby' => 'sa',],
            ['rssite' => 'PIGRP-SP', 'rsbaynum' => 'A1', 'createdate' => Carbon::now(),'createdby' => 'sa',],
            ['rssite' => 'PIGRP-SP', 'rsbaynum' => 'A2', 'createdate' => Carbon::now(),'createdby' => 'sa',],
            ['rssite' => 'PIGRP-SP', 'rsbaynum' => 'A3', 'createdate' => Carbon::now(),'createdby' => 'sa',],
        ]);
    }
}
