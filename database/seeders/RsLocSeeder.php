<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RsLocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('rslocation')->insert([
        //     ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG1', 'rsbaynum' => 'A1', 'rsloc' => 'A1-L1-C01-P01', 'rsdesc' => 'Rack 1', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
        //     ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG1', 'rsbaynum' => 'A1', 'rsloc' => 'A1-L1-C01-P02', 'rsdesc' => 'Rack 2', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
        //     ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG1', 'rsbaynum' => 'A2', 'rsloc' => 'A2-L2-C01-P01', 'rsdesc' => 'Rack 3', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
        //     ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG1', 'rsbaynum' => 'A2', 'rsloc' => 'A2-L2-C01-P02', 'rsdesc' => 'Rack 4', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
        //     ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG1', 'rsbaynum' => 'A3', 'rsloc' => 'A3-L3-C01-P01', 'rsdesc' => 'Rack 5', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
        //     ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-BLDG1', 'rsbaynum' => 'A3', 'rsloc' => 'A3-L3-C01-P02', 'rsdesc' => 'Rack 6', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
        //     ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL1', 'rsbaynum' => 'A2', 'rsloc' => 'A2-L1-C01-P01', 'rsdesc' => 'Rack 1', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
        //     ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-BL1', 'rsbaynum' => 'A2', 'rsloc' => 'A2-L1-C01-P02', 'rsdesc' => 'Rack 2', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],

            
        // ]);

        // $path = database_path('seeders/csv/rslocation_data.csv');
        // $file = fopen($path, 'r');

        // // Skip header
        // fgetcsv($file);

        // $batch = [];
        // $batchSize = 1000;

        // while (($row = fgetcsv($file, 0, ",")) !== false) {
        //     $batch[] = [
        //         'rssite'     => $row[0],
        //         'rswhse'     => $row[1],
        //         'rsbaynum'   => $row[2],
        //         'rsloc'      => $row[3],
        //         'rsdesc'     => $row[4] !== '' ? $row[4] : null,
        //         'qty'        => (int) $row[5],
        //         'createdate' => date('Y-m-d', strtotime($row[6])),
        //         'createdby'  => $row[7],
        //     ];

        //     if (count($batch) >= $batchSize) {
        //         DB::table('rslocation')->insert($batch);
        //         $batch = [];
        //     }
        // }

        // if (!empty($batch)) {
        //     DB::table('rslocation')->insert($batch);
        // }

        // fclose($file);

        $folder = database_path('seeders/seed_data');
        $files = glob($folder . '/*.csv');

        foreach ($files as $filePath) {
            $this->seedFromCsv($filePath);
        }
    }
    
    private function seedFromCsv(string $filePath): void
    {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return;
        }

        // Skip header row (first line)
        fgetcsv($handle, 0, ",");

        $batch = [];
        $batchSize = 250; // Safe for SQL Server (8 cols × 250 rows = 2000 params)

        while (($row = fgetcsv($handle, 0, ",")) !== false) {
            // Skip empty or malformed lines
            if (count($row) < 8) {
                continue;
            }

            $batch[] = [
                'rssite'     => $row[0],
                'rswhse'     => $row[1],
                'rsbaynum'   => $row[2],
                'rsloc'      => $row[3],
                'rsdesc'     => $row[4] !== '' ? $row[4] : null,
                'qty'        => (int) $row[5],
                'createdate' => date('Y-m-d', strtotime($row[6])),
                'createdby'  => $row[7],
            ];

            // Insert once batch limit is reached
            if (count($batch) >= $batchSize) {
                DB::table('rslocation')->insert($batch);
                $batch = [];
            }
        }

        // Insert remaining rows
        if (!empty($batch)) {
            DB::table('rslocation')->insert($batch);
        }

        fclose($handle);
    }
}
