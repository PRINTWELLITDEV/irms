<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RswhseSeeder extends Seeder
{
    public function run(): void
    {
        $createdate = Carbon::create(2025, 9, 26);
        $data = [
            //Fortune Official Warehouse
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-BLDG8', 'name' => 'FBIC-BLDG#8', 'addr' => 'FPC BICUTAN WHSE BLDG #8', 'createdate' => $createdate, 'createdby' => 'sa'],
            
            //PI and PWPC sample warehouses
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
