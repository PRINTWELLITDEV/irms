<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RswhseSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-A1', 'name' => 'FBIC-BLDG#1', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-A2', 'name' => 'FBIC-BLDG#2', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-A3', 'name' => 'FBIC-BLDG#3', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-A4', 'name' => 'FBIC-BLDG#4', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B1', 'name' => 'FBIC-BLDG#1', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B2', 'name' => 'FBIC-BLDG#2', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B3', 'name' => 'FBIC-BLDG#3', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B4', 'name' => 'FBIC-BLDG#4', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B5', 'name' => 'FBIC-BLDG#5', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B6', 'name' => 'FBIC-BLDG#6', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B7', 'name' => 'FBIC-BLDG#7', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B8', 'name' => 'FBIC-BLDG#8', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-A1', 'name' => 'PBIC-BLDG#1', 'addr' => 'Dansalan Str., Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-A2', 'name' => 'PBIC-BLDG#2', 'addr' => 'Dansalan Str., Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-A3', 'name' => 'PBIC-BLDG#3', 'addr' => 'Dansalan Str., Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-A4', 'name' => 'PBIC-BLDG#4', 'addr' => 'Dansalan Str., Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-B1', 'name' => 'PBIC-BLDG#1', 'addr' => 'Dansalan Str., Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-B2', 'name' => 'PBIC-BLDG#2', 'addr' => 'Dansalan Str., Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-B3', 'name' => 'PBIC-BLDG#3', 'addr' => 'Dansalan Str., Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-B4', 'name' => 'PBIC-BLDG#4', 'addr' => 'Dansalan Str., Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-B5', 'name' => 'PBIC-BLDG#5', 'addr' => 'Dansalan Str., Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-A1', 'name' => 'PGBIC-BLDG#1', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-A2', 'name' => 'PGBIC-BLDG#2', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-A3', 'name' => 'PGBIC-BLDG#3', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-A4', 'name' => 'PGBIC-BLDG#4', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-B1', 'name' => 'PGBIC-BLDG#1', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-B2', 'name' => 'PGBIC-BLDG#2', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-B3', 'name' => 'PGBIC-BLDG#3', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-B4', 'name' => 'PGBIC-BLDG#4', 'addr' => 'Mamplasan, Laguna', 'createdate' => null, 'createdby' => 'sa'],
        ];

        foreach ($data as $row) {
            DB::table('rswhse')->updateOrInsert(
                ['rssite' => $row['rssite'], 'rswhse' => $row['rswhse']], // unique keys
                $row // values to insert or update
            );
        }
    }
}
