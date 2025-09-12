<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RswhseSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-A1', 'name' => 'FBIC-BLDG#1', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-A2', 'name' => 'FBIC-BLDG#2', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-A3', 'name' => 'FBIC-BLDG#3', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-A4', 'name' => 'FBIC-BLDG#4', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B1', 'name' => 'FBIC-BLDG#1', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B2', 'name' => 'FBIC-BLDG#2', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B3', 'name' => 'FBIC-BLDG#3', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B4', 'name' => 'FBIC-BLDG#4', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B5', 'name' => 'FBIC-BLDG#5', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B6', 'name' => 'FBIC-BLDG#6', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B7', 'name' => 'FBIC-BLDG#7', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'None'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-B8', 'name' => 'FBIC-BLDG#8', 'addr' => 'Paranaque City', 'createdate' => null, 'createdby' => 'Me'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-B1', 'name' => 'PBIC-BLDG#1', 'addr' => 'Dansalan Str., Mandaluyong City, Metro Manila', 'createdate' => null, 'createdby' => 'Me'],
        ];

        foreach ($data as $row) {
            DB::table('rswhse')->updateOrInsert(
                ['rssite' => $row['rssite'], 'rswhse' => $row['rswhse']], // unique keys
                $row // values to insert or update
            );
        }
    }
}
