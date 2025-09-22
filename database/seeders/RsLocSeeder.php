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
        DB::table('rslocation')->insert([
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-A1', 'rsbaynum' => 'A1', 'rsloc' => 'A1-L1-C01-P01', 'rsdesc' => 'Rack 1', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-A1', 'rsbaynum' => 'A1', 'rsloc' => 'A1-L1-C01-P02', 'rsdesc' => 'Rack 2', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-A1', 'rsbaynum' => 'A2', 'rsloc' => 'A1-L2-C01-P01', 'rsdesc' => 'Rack 3', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-A1', 'rsbaynum' => 'A2', 'rsloc' => 'A1-L2-C01-P02', 'rsdesc' => 'Rack 4', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-A1', 'rsbaynum' => 'A3', 'rsloc' => 'A1-L3-C01-P01', 'rsdesc' => 'Rack 5', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
            ['rssite' => 'PI-SP', 'rswhse' => 'PBIC-A1', 'rsbaynum' => 'A3', 'rsloc' => 'A1-L3-C01-P02', 'rsdesc' => 'Rack 6', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-A2', 'rsbaynum' => 'A3', 'rsloc' => 'A1-L4-C01-P01', 'rsdesc' => 'Rack 1', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
            ['rssite' => 'FP-SP', 'rswhse' => 'FBIC-A2', 'rsbaynum' => 'A3', 'rsloc' => 'A1-L4-C01-P02', 'rsdesc' => 'Rack 2', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-A3', 'rsbaynum' => 'A3', 'rsloc' => 'A1-L1-C01-P01', 'rsdesc' => 'Rack 1', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
            ['rssite' => 'PIGRP-SP', 'rswhse' => 'PGBIC-A3', 'rsbaynum' => 'A3', 'rsloc' => 'A1-L1-C01-P02', 'rsdesc' => 'Rack 2', 'qty' => 0, 'createdate' => now(), 'createdby' => 'sa'],
        ]);
    }
}
