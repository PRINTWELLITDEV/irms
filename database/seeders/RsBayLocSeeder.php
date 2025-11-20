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
        $createdate = Carbon::now();

        // PI-SP sample bay locations
        $piSpBays = [
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG1', 'rsbaynum' => 'A1'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG1', 'rsbaynum' => 'A2'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG1', 'rsbaynum' => 'A3'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG2', 'rsbaynum' => 'B1'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG2', 'rsbaynum' => 'B2'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG2', 'rsbaynum' => 'B3'],
        ];

        // PIGRP-SP sample bay locations
        $pigrpSpBays = [
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL1', 'rsbaynum' => 'A1'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL1', 'rsbaynum' => 'A2'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL1', 'rsbaynum' => 'A3'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL2', 'rsbaynum' => 'B1'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL2', 'rsbaynum' => 'B2'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL2', 'rsbaynum' => 'B3'],
        ];

        // FP-SP Official Fortune Bays
        $fpSpBayNums = [
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

        $fpSpBays = [];
        foreach ($fpSpBayNums as $bayNum) {
            $fpSpBays[] = [
                'rssite' => 'FP-SP',
                'rswhse' => 'FBIC-BLDG8',
                'rsbaynum' => $bayNum
            ];
        }

        // Combine all bay data
        $allBays = array_merge($piSpBays, $pigrpSpBays, $fpSpBays);

        // Add common fields to all records
        $bayData = [];
        foreach ($allBays as $bay) {
            $bayData[] = array_merge($bay, [
                'createdate' => $createdate,
                'createdby' => 'sa'
            ]);
        }

        // Insert using updateOrInsert to prevent duplicates
        foreach ($bayData as $row) {
            DB::table('rsbayloc')->updateOrInsert(
                [
                    'rssite' => $row['rssite'], 
                    'rswhse' => $row['rswhse'], 
                    'rsbaynum' => $row['rsbaynum']
                ], // unique keys
                $row // values to insert or update
            );
        }
    }
}
