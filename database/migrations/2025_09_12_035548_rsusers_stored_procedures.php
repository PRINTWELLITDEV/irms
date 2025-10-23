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
                u.rssite,
                u.userid,
                u.name,
                u.email,
                u.department,
                u.section,
                u.position,
                u.level,
                u.gender,
                u.create_date,
                u.profile_pic_url,
                s.rssite_desc,
                s.address,
                s.logo_pic_url
            FROM rsusers u
            INNER JOIN irms_site s ON s.rssite = u.rssite
            WHERE u.userid <> \'sa\';
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
                @department NVARCHAR(255),
                @section NVARCHAR(255),
                @position NVARCHAR(255),
                @gender NVARCHAR(10),
                @profile_pic_url NVARCHAR(255),
                @create_date DATETIME,
                @created_by NVARCHAR(8),
                @level INT
            AS
            BEGIN
                INSERT INTO rsusers (
                    rssite, userid, name, password, email, department, section, position, gender, profile_pic_url, create_date, updated_by, level
                ) VALUES (
                    @rssite, @userid, @name, @password, @email, @department, @section, @position, @gender, @profile_pic_url, @create_date, @created_by, @level
                );
            END
        ');

        // Create sp_show_user
        DB::unprepared("IF OBJECT_ID('sp_select_user', 'P') IS NOT NULL DROP PROCEDURE sp_select_user");
        DB::unprepared('
            CREATE PROCEDURE sp_select_user
                @userid NVARCHAR(8)
            AS
            SELECT
                u.rssite,
                u.userid,
                u.name,
                u.email,
                u.department,
                u.section,
                u.position,
                u.level,
                u.gender,
                u.create_date,
                u.profile_pic_url,
                s.rssite_desc,
                s.address,
                s.logo_pic_url
            FROM rsusers u
            INNER JOIN irms_site s ON s.rssite = u.rssite
            WHERE u.userid = @userid;
        ');
        // Create sp_update_user
        DB::unprepared("IF OBJECT_ID('sp_update_user', 'P') IS NOT NULL DROP PROCEDURE sp_update_user");
        DB::unprepared("
            CREATE PROCEDURE sp_update_user
                @rssite NVARCHAR(8),
                @userid NVARCHAR(8),
                @name NVARCHAR(255),
                @email NVARCHAR(255),
                @department NVARCHAR(255),
                @section NVARCHAR(255),
                @position NVARCHAR(255),
                @gender NVARCHAR(10),
                @level INT,
                @password NVARCHAR(255),
                @updated_by NVARCHAR(8)
            AS
            BEGIN
                UPDATE rsusers
                SET
                    rssite = @rssite,
                    name = @name,
                    email = @email,
                    department = @department,
                    section = @section,
                    position = @position,
                    gender = @gender,
                    level = @level,
                    password = CASE WHEN @password IS NOT NULL AND @password <> '' THEN @password ELSE password END,
                    updated_by = @updated_by
                WHERE userid = @userid;
            END
        ");

        // Create sp_update_profile
        DB::unprepared("IF OBJECT_ID('sp_update_profile', 'P') IS NOT NULL DROP PROCEDURE sp_update_profile");
        DB::unprepared('
            CREATE PROCEDURE sp_update_profile
                @rssite NVARCHAR(8),
                @userid NVARCHAR(8),
                @name NVARCHAR(255) = NULL,
                @gender NVARCHAR(10) = NULL,
                @department NVARCHAR(50) = NULL,
                @section NVARCHAR(50) = NULL,
                @position NVARCHAR(50) = NULL,
                @updated_by NVARCHAR(8) = NULL
            AS
            BEGIN
                SET NOCOUNT ON;
                UPDATE rsusers
                SET
                    name = COALESCE(@name, name),
                    gender = COALESCE(@gender, gender),
                    department = COALESCE(@department, department),
                    section = COALESCE(@section, section),
                    position = COALESCE(@position, position),
                    updated_date = GETDATE(),
                    updated_by = @updated_by
                WHERE rssite = @rssite AND userid = @userid;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("IF OBJECT_ID('sp_view_users', 'P') IS NOT NULL DROP PROCEDURE sp_view_users");
        DB::unprepared("IF OBJECT_ID('sp_add_user', 'P') IS NOT NULL DROP PROCEDURE sp_add_user");
        DB::unprepared("IF OBJECT_ID('sp_select_user', 'P') IS NOT NULL DROP PROCEDURE sp_select_user");
        DB::unprepared("IF OBJECT_ID('sp_update_user', 'P') IS NOT NULL DROP PROCEDURE sp_update_user");
        DB::unprepared("IF OBJECT_ID('sp_update_profile', 'P') IS NOT NULL DROP PROCEDURE sp_update_profile");
    }
};
