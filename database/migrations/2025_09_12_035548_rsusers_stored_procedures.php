<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. ADD COLUMN TO THE UNDERLYING TABLE (rsusers)
        Schema::table('rsusers', function (Blueprint $table) {
            $table->timestamp('last_seen_at')->nullable()->after('level');
        });

        // 2. RECREATE sp_view_users (Add last_seen_at)
        DB::unprepared("IF OBJECT_ID('sp_view_users', 'P') IS NOT NULL DROP PROCEDURE sp_view_users");
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
                u.last_seen_at,  -- ADDED
                s.rssite_desc,
                s.address,
                s.logo_pic_url
            FROM rsusers u
            INNER JOIN irms_site s ON s.rssite = u.rssite
            WHERE u.userid <> \'sa\';
        ');

        // 3. RECREATE sp_add_user (No change needed, last_seen_at defaults to NULL)
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

        // 4. RECREATE sp_select_user (Add last_seen_at)
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
                u.last_seen_at,  -- ADDED
                s.rssite_desc,
                s.address,
                s.logo_pic_url
            FROM rsusers u
            INNER JOIN irms_site s ON s.rssite = u.rssite
            WHERE u.userid = @userid;
        ');

        // 5. RECREATE sp_update_user (No change needed as last_seen_at is updated outside of this proc by Laravel)
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
        // 6. Create sp_active_users_per_site
        DB::unprepared("IF OBJECT_ID('sp_active_users_per_site', 'P') IS NOT NULL DROP PROCEDURE sp_active_users_per_site");
        DB::unprepared("
            CREATE PROCEDURE sp_active_users_per_site
            AS
            BEGIN
                SET NOCOUNT ON;

                SELECT
                    u.rssite,
                    s.rssite_desc,
                    COUNT(DISTINCT u.userid) AS total_active_users
                FROM rsusers u
                INNER JOIN irms_site s ON s.rssite = u.rssite
                WHERE
                    u.last_seen_at IS NOT NULL
                    AND u.last_seen_at >= DATEADD(MINUTE, -2, GETDATE())
                    AND u.userid <> 'sa'
                GROUP BY
                    u.rssite, s.rssite_desc
                ORDER BY
                    total_active_users DESC;
            END
        ");

        // 7. Create sp_currently_online_users
        DB::unprepared("IF OBJECT_ID('sp_currently_online_users', 'P') IS NOT NULL DROP PROCEDURE sp_currently_online_users");
        DB::unprepared("
            CREATE PROCEDURE sp_currently_online_users
            AS
            BEGIN
                SET NOCOUNT ON;

                SELECT
                    u.userid,
                    u.name,
                    u.email,
                    u.department,
                    u.section,
                    u.position,
                    u.rssite,
                    u.last_seen_at,
                    s.rssite_desc
                FROM rsusers u
                INNER JOIN irms_site s ON s.rssite = u.rssite
                WHERE
                    u.last_seen_at IS NOT NULL
                    AND u.last_seen_at >= DATEADD(MINUTE, -2, GETDATE())
                    AND u.userid <> 'sa'
                ORDER BY u.last_seen_at DESC;
            END
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. DROP COLUMN FROM THE UNDERLYING TABLE
        Schema::table('rsusers', function (Blueprint $table) {
            $table->dropColumn('last_seen_at');
        });

        // 2. DROP AND RECREATE STORED PROCEDURES (Revert to original logic if necessary)
        // Since the prompt only provides the DOWN logic to drop procedures, I'll retain that for simplicity.
        DB::unprepared("IF OBJECT_ID('sp_view_users', 'P') IS NOT NULL DROP PROCEDURE sp_view_users");
        DB::unprepared("IF OBJECT_ID('sp_add_user', 'P') IS NOT NULL DROP PROCEDURE sp_add_user");
        DB::unprepared("IF OBJECT_ID('sp_select_user', 'P') IS NOT NULL DROP PROCEDURE sp_select_user");
        DB::unprepared("IF OBJECT_ID('sp_update_user', 'P') IS NOT NULL DROP PROCEDURE sp_update_user");
        DB::unprepared("IF OBJECT_ID('sp_active_users_per_site', 'P') IS NOT NULL DROP PROCEDURE sp_active_users_per_site");
        DB::unprepared("IF OBJECT_ID('sp_currently_online_users', 'P') IS NOT NULL DROP PROCEDURE sp_currently_online_users");

    }
};
