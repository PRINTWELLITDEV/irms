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
                'password' => '$2b$12$ADR9sUrhfFJ4XFTziOlpPuFVpr6j3wAv/.7Lp5TOgHBUrvXQ5EZJC',
                'email' => 'trick@gmail.com',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => '2025-09-09 09:33:32',
                'updated_date' => '2025-09-09 12:04:16',
                'updated_by' => null,
                'updated_by_sql' => null,
                'gender' => 'male',
                'profile_pic_url' => 'uploads/user-profile/ppc1181.png',
                'remember_token' => null,
            ],
            [
                'rssite' => 'PI-SP',
                'userid' => 'Hello123',
                'name' => 'Ern Dave Alvarez',
                'password' => '$2y$12$rNyvrx1IBzjsWxyYWvBXfegSr7Szm/luUjgGS877CSPusu9QYBNga',
                'email' => 'ErnDave.ALvarez@printwell.com.ph',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => null,
                'updated_date' => null,
                'updated_by' => null,
                'updated_by_sql' => null,
                'gender' => 'female',
                'profile_pic_url' => null,
                'remember_token' => null,
            ],
            [
                'rssite' => 'PI-SP',
                'userid' => 'PPC1187',
                'name' => 'Aron Suarnaba',
                'password' => 'PPC1187', // ⚠️ should be hashed in real use
                'email' => 'aron@gmail.com',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => '2025-09-09 10:48:05',
                'updated_date' => '2025-09-09 11:29:02',
                'updated_by' => null,
                'updated_by_sql' => null,
                'gender' => 'male',
                'profile_pic_url' => null,
                'remember_token' => null,
            ],
            [
                'rssite' => 'PI-SP',
                'userid' => 'PPC1190',
                'name' => 'Mico Limbanganon',
                'password' => '$2y$12$tkGgdTkNlkIQAA6JYDTwiub6zHSWtiGtw2VKtjqysRLbWcykb6izG',
                'email' => 'mico.limbanganon@printwell.com.ph',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => null,
                'updated_date' => null,
                'updated_by' => 'PPC1190',
                'updated_by_sql' => null,
                'gender' => 'male',
                'profile_pic_url' => null,
                'remember_token' => 'fPVypke8vjjICtjViQQGJGE0vEIEoYanWu5Kllo7D3qxSmKHLLniExDQ8xyY',
            ],
            [
                'rssite' => 'PI-SP',
                'userid' => 'PPR1181',
                'name' => 'Trick Torres',
                'password' => '$2y$12$gYdZ2lyIHg9NmSi8KefNbOlY6qouei3Cf5NXNLdLZyki.HqKNpI4C',
                'email' => 'trick@printwell.com.ph',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => null,
                'updated_date' => null,
                'updated_by' => 'PPR1181',
                'updated_by_sql' => null,
                'gender' => 'male',
                'profile_pic_url' => 'uploads/user-profile/PPR1181.png',
                'remember_token' => 'bGkoy5eWbHwJgngAsmJXRNFIvAzqHk0hxUOdFSvq22dXOUx4Ju2AARw1QiWv',
            ],
        ]);
    }
}
