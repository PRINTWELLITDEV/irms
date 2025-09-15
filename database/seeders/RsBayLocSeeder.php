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
            [
                'rssite' => 'PI-SP', // Printwell, Inc.
                'rsbaynum' => 'A1',
                'createdate' => Carbon::now(),
                'createdby' => 'sa',
            ],
            [
                'rssite' => 'FP-SP', // Fortune Packaging Corp.
                'rsbaynum' => 'A2',
                'createdate' => Carbon::now(),
                'createdby' => 'sa',
            ],
            [
                'rssite' => 'PIGRP-SP', // Printwell Packaging Corp.
                'rsbaynum' => 'A3',
                'createdate' => Carbon::now(),
                'createdby' => 'sa',
            ],
        ]);
    }
}
