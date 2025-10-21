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
        // Drop if exists first
        DB::unprepared("IF OBJECT_ID('dbo.sp_rsuser_register', 'P') IS NOT NULL DROP PROCEDURE dbo.sp_rsuser_register;");

        // Create stored procedure for inserting a user
        DB::unprepared("
            CREATE PROCEDURE dbo.sp_rsuser_register
                @rssite NVARCHAR(8),
                @userid NVARCHAR(8),
                @name NVARCHAR(255),
                @password NVARCHAR(255),
                @email NVARCHAR(255),
                @department NVARCHAR(50) = NULL,
                @section NVARCHAR(50) = NULL,
                @position NVARCHAR(50) = NULL,
                @level INT,
                @gender NVARCHAR(10) = NULL,
                @profile_pic_url NVARCHAR(MAX) = NULL
            AS
            BEGIN
                SET NOCOUNT ON;
                INSERT INTO rsusers (
                    rssite, userid, name, password, email,
                    department, section, position, level,
                    create_date, gender, profile_pic_url
                ) VALUES (
                    @rssite, @userid, @name, @password, @email,
                    @department, @section, @position, @level,
                    GETDATE(), @gender, @profile_pic_url
                );
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("IF OBJECT_ID('dbo.sp_rsuser_register', 'P') IS NOT NULL DROP PROCEDURE dbo.sp_rsuser_register;");
    }
};
