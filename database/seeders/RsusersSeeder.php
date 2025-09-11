<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RsusersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rsusers')->insert([
            [
                'rssite' => 'FP-SP',
                'userid' => 'PPC1181',
                'name' => 'Trick Torres',
                'password' => 'PPC1181', // ⚠️ raw password (not recommended for production)
                'email' => 'trick@gmail.com',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => '2025-09-09 09:33:32',
                'updated_date' => '2025-09-09 12:04:16',
                'updated_by' => null,          // set later by Laravel model
                'updated_by_sql' => null,      // set later by SQL trigger
                'user_type' => null,
                'gender' => 'male',
                'profile_pic_url' => 'uploads/user-profile/ppc1181.png',
                'remember_token' => NULL,
            ],
            [
                'rssite' => 'PI-SP',
                'userid' => 'PPC1187',
                'name' => 'Aron Suarnaba',
                'password' => 'PPC1187', // ⚠️ raw password
                'email' => 'aron@gmail.com',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => '2025-09-09 10:48:05',
                'updated_date' => '2025-09-09 11:29:02',
                'updated_by' => null,
                'updated_by_sql' => null,
                'user_type' => null,
                'gender' => 'male',
                'profile_pic_url' => null,
                'remember_token' => Null,
            ],
        ]);
    }
}
