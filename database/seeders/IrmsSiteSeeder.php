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
        // DB::table('irms_site')->truncate();
        DB::table('irms_site')->insert([
            [
                'rssite' => 'PI-SP',
                'rssite_desc' => 'Printwell, Inc.',
                'address' => '38 Dansalan St., Mandaluyong City 1501',
                'logo_pic_url' => 'uploads/sites-img/pi-logo.png',
                'site_link' => 'http://www.printwell.com.ph/',
                'create_date' => '2025-09-09 09:30:00',
            ],
            [
                'rssite' => 'FP-SP',
                'rssite_desc' => 'Fortune Packaging Corp.',
                'address' => 'Severina Industrial Subdivision, 20 Main Avenue, Km 16 South Luzon Expy, Parañaque, 1700 Metro Manila',
                'logo_pic_url' => 'uploads/sites-img/fpc-logo.png',
                'site_link' => 'https://www.fortunepackaging.com/',
                'create_date' => '2025-09-09 09:31:00',
            ],
            [
                'rssite' => 'PIGRP-SP',
                'rssite_desc' => 'Printwell Packaging Corp.',
                'address' => 'LIIP Ave, Biñan, 4024 Laguna',
                'logo_pic_url' => 'uploads/sites-img/pwpc-logo.png',
                'site_link' => 'https://printwellpack.com/',
                'create_date' => '2025-09-09 09:32:00',
            ],
        ]);
    }
}
