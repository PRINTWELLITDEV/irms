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
            ['rssite' => 'PIGRP-SP', 'rsbaynum' => 'A1', 'createdate' => Carbon::now(),'createdby' => 'sa',],
            ['rssite' => 'PIGRP-SP', 'rsbaynum' => 'A2', 'createdate' => Carbon::now(),'createdby' => 'sa',],
            ['rssite' => 'PIGRP-SP', 'rsbaynum' => 'A3', 'createdate' => Carbon::now(),'createdby' => 'sa',],
        ]);

        // Load Official Fortune Bays
        $fpSpBays = [
            'A1','A2',
            'B1','B2','B3','B4',
            'C1','C2','C3','C4',
            'D1','D2',
            'E1','E2',
            'F1','F2','F3','F4',
            'G1','G2','G3','G4',
            'H1','H2','H3','H4',
            'I1','I2','I3','I4',
            'J1','J2','J3','J4'
        ];

        $fpSpBayData = [];
        $createdate = "2025-09-26";

        foreach ($fpSpBays as $bay) {
            $fpSpBayData[] = [
                'rssite' => 'FP-SP',
                'rsbaynum' => $bay,
                'createdate' => $createdate,
                'createdby' => 'sa',
            ];
        }
        DB::table('rsbayloc')->insert($fpSpBayData);
    }
}
