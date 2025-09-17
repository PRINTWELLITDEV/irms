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
                'rssite' => 'PI-SP',
                'userid' => 'sa',
                'name' => 'PI Super Admin',
                'password' => '$2y$12$X5z.hWskCAkt6QBq0K2C8O7lrHJLUVEkWZxgFR97b.XJX4KplN0du',
                'email' => 'printwellitdev@gmail.com',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => null,
                'updated_date' => null,
                'updated_by' => null,
                'updated_by_sql' => null,
                'gender' => null,
                'profile_pic_url' => 'uploads/user-profile/super_admin.png',
                'remember_token' => null,
            ],
            [
                'rssite' => 'FP-SP',
                'userid' => 'PPR1181',
                'name' => 'Trick Torres',
                'password' => '$2y$12$gYdZ2lyIHg9NmSi8KefNbOlY6qouei3Cf5NXNLdLZyki.HqKNpI4C',
                'email' => 'patrick.torres@printwell.com.ph',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => '2025-09-09 09:33:32',
                'updated_date' => null,
                'updated_by' => null,
                'updated_by_sql' => null,
                'gender' => 'male',
                'profile_pic_url' => 'uploads/user-profile/PPR1181.png',
                'remember_token' => null,
            ],
            [
                'rssite' => 'PI-SP',
                'userid' => 'PPC1187',
                'name' => 'Aron Suarnaba',
                'password' => '$2y$12$RRVhqnVTQl.l1iYtrycViuugrYf16QgawzIc3gwcdc3O73n0GA2mS',
                'email' => 'aron.suarnaba@printwell.com',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => '2025-09-09 09:33:32',
                'updated_date' => null,
                'updated_by' => null,
                'updated_by_sql' => null,
                'gender' => 'male',
                'profile_pic_url' => 'uploads/user-profile/PPC1187.png',
                'remember_token' => null,
            ],
            [
                'rssite' => 'PI-SP',
                'userid' => 'PPC1190',
                'name' => 'Mico Limbanganon',
                'password' => '$2y$12$tkGgdTkNlkIQAA6JYDTwiub6zHSWtiGtw2VKtjqysRLbWcykb6izG',
                'email' => 'mico.limbanganon@printwell.com',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => '2025-09-09 09:33:32',
                'updated_date' => null,
                'updated_by' => null,
                'updated_by_sql' => null,
                'gender' => 'male',
                'profile_pic_url' => null,
                'remember_token' => null,
            ],
            [
                'rssite' => 'PI-SP',
                'userid' => 'guest',
                'name' => 'Guest User',
                'password' => '$2y$12$tf0CU.Gp5eYDHyT2m7vSEumFMpzVzYw4W5IIRLH9vobKDKtd1KABy',
                'email' => 'guest@email.com',
                'email_verified_at' => null,
                'level' => 1,
                'create_date' => '2025-09-16 11:42:57.577',
                'updated_date' => null,
                'updated_by' => null,
                'updated_by_sql' => null,
                'gender' => null,
                'profile_pic_url' => 'uploads/user-profile/68c94d415febc_guest.png',
                'remember_token' => null,
            ],
        ]);
    }
}
