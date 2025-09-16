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
        DB::unprepared("IF OBJECT_ID('sp_view_users', 'P') IS NOT NULL DROP PROCEDURE sp_view_users");
        // Create sp_view_users (no parentheses, no BEGIN/END needed for single statement)
        DB::unprepared('
            CREATE PROCEDURE sp_view_users
            AS
            SELECT 
                u.*,
                s.rssite_desc,
                s.address,
				s.logo_pic_url
            FROM rsusers u
            INNER JOIN irms_site s ON u.rssite = s.rssite;
        ');

        // Create sp_add_user (use @param, no IN, and use NVARCHAR for Unicode support)
        DB::unprepared("IF OBJECT_ID('sp_add_user', 'P') IS NOT NULL DROP PROCEDURE sp_add_user");
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

        // Create sp_show_user
        DB::unprepared("IF OBJECT_ID('sp_show_user', 'P') IS NOT NULL DROP PROCEDURE sp_show_user");
        DB::unprepared('
            CREATE PROCEDURE sp_show_user
                @userid NVARCHAR(8)
            AS
            SELECT 
                u.*,
                s.rssite_desc,
                s.address,
				s.logo_pic_url
            FROM rsusers u
            INNER JOIN irms_site s ON s.rssite = u.rssite
            WHERE u.userid = @userid;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("IF OBJECT_ID('sp_view_users', 'P') IS NOT NULL DROP PROCEDURE sp_view_users");
        DB::unprepared("IF OBJECT_ID('sp_add_user', 'P') IS NOT NULL DROP PROCEDURE sp_add_user");
        DB::unprepared("IF OBJECT_ID('sp_show_user', 'P') IS NOT NULL DROP PROCEDURE sp_show_user");
    }
};
