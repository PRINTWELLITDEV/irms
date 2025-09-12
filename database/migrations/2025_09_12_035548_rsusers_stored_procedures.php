<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop procedures if they exist (SQL Server syntax)
        DB::unprepared("IF OBJECT_ID('sp_get_users', 'P') IS NOT NULL DROP PROCEDURE sp_get_users");
        DB::unprepared("IF OBJECT_ID('sp_add_user', 'P') IS NOT NULL DROP PROCEDURE sp_add_user");

        // Create sp_get_users (no parentheses, no BEGIN/END needed for single statement)
        DB::unprepared('
            CREATE PROCEDURE sp_get_users
            AS
            SELECT 
                u.profile_pic_url,
                u.userid,
                u.name,
                u.email,
                u.level,
                s.rssite_desc
            FROM rsusers u
            LEFT JOIN irms_site s ON u.rssite = s.rssite;
        ');

        // Create sp_add_user (use @param, no IN, and use NVARCHAR for Unicode support)
        DB::unprepared('
            CREATE PROCEDURE sp_add_user
                @rssite NVARCHAR(8),
                @userid NVARCHAR(8),
                @name NVARCHAR(255),
                @password NVARCHAR(255),
                @email NVARCHAR(255),
                @gender NVARCHAR(10),
                @profile_pic_url NVARCHAR(255)
            AS
            INSERT INTO rsusers (rssite, userid, name, password, email, gender, profile_pic_url)
            VALUES (@rssite, @userid, @name, @password, @email, @gender, @profile_pic_url);
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("IF OBJECT_ID('sp_get_users', 'P') IS NOT NULL DROP PROCEDURE sp_get_users");
        DB::unprepared("IF OBJECT_ID('sp_add_user', 'P') IS NOT NULL DROP PROCEDURE sp_add_user");
    }
};
