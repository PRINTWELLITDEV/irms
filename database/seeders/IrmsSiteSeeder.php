<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IrmsSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('irms_site')->insert([
            [
                'rssite' => 'PI-SP',
                'rssite_desc' => 'Printwell, Inc.',
                'address' => null,
            ],
            [
                'rssite' => 'FP-SP',
                'rssite_desc' => 'Fortune Packaging Corp.',
                'address' => null,
            ],
            [
                'rssite' => 'PIGRP-SP',
                'rssite_desc' => 'Printwell Packaging Corp.',
                'address' => null,
            ],
        ]);
    }
}
