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
        DB::unprepared("IF OBJECT_ID('sp_update_profile', 'P') IS NOT NULL DROP PROCEDURE sp_update_profile");
    }
};
