<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RswhseSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-BLDG1', 'name' => 'FBIC-BLDG1', 'addr' => 'FPC BICUTAN WHSE BLDG 1', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-BLDG2', 'name' => 'FBIC-BLDG2', 'addr' => 'FPC BICUTAN WHSE BLDG 2', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-BLDG3', 'name' => 'FBIC-BLDG3', 'addr' => 'FPC BICUTAN WHSE BLDG 3', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-BLDG4', 'name' => 'FBIC-BLDG4', 'addr' => 'FPC BICUTAN WHSE BLDG 4', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-BLDG5', 'name' => 'FBIC-BLDG5', 'addr' => 'FPC BICUTAN WHSE BLDG 5', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-BLDG6', 'name' => 'FBIC-BLDG6', 'addr' => 'FPC BICUTAN WHSE BLDG 6', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-BLDG7', 'name' => 'FBIC-BLDG7', 'addr' => 'FPC BICUTAN WHSE BLDG 7', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-BLDG8', 'name' => 'FBIC-BLDG8', 'addr' => 'FPC BICUTAN WHSE BLDG 8', 'createdate' => null, 'createdby' => 'sa'],
            
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG1', 'name' => 'PBIC-BLDG1', 'addr' => 'Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG2', 'name' => 'PBIC-BLDG2', 'addr' => 'Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG3', 'name' => 'PBIC-BLDG3', 'addr' => 'Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG4', 'name' => 'PBIC-BLDG4', 'addr' => 'Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],

            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL1', 'name' => 'PGBIC-BLDG1', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL2', 'name' => 'PGBIC-BLDG2', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL3', 'name' => 'PGBIC-BLDG3', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL4', 'name' => 'PGBIC-BLDG4', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            
        ];

        foreach ($data as $row) {
            DB::table('rswhse')->updateOrInsert(
                ['rssite' => $row['rssite'], 'rswhse' => $row['rswhse']], // unique keys
                $row // values to insert or update
            );
        }
    }
}
