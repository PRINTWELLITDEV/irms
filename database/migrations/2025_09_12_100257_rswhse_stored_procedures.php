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
        // Drop procedures if they exist before creating (SQL Server syntax)
        DB::unprepared("IF OBJECT_ID('sp_add_whse', 'P') IS NOT NULL DROP PROCEDURE sp_add_whse;");
        DB::unprepared('
            CREATE PROCEDURE sp_add_whse
                @p_rssite VARCHAR(8),
                @p_rswhse VARCHAR(10),
                @p_name VARCHAR(30),
                @p_addr VARCHAR(60),
                @p_createdby VARCHAR(30)
            AS
            BEGIN
                INSERT INTO rswhse (rssite, rswhse, name, addr, createdate, createdby)
                VALUES (
                    @p_rssite,
                    @p_rswhse,
                    @p_name,
                    @p_addr,
                    GETDATE(),
                    @p_createdby
                );
            END
        ');

        DB::unprepared("IF OBJECT_ID('sp_view_whse', 'P') IS NOT NULL DROP PROCEDURE sp_view_whse;");
        DB::unprepared('
            CREATE PROCEDURE sp_view_whse
            AS
            BEGIN
                SELECT w.rssite, rswhse, name, addr, logo_pic_url
				FROM 
				rswhse w
				INNER JOIN irms_site s ON s.rssite = w.rssite
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("IF OBJECT_ID('sp_add_whse', 'P') IS NOT NULL DROP PROCEDURE sp_add_whse;");
        DB::unprepared("IF OBJECT_ID('sp_view_whse', 'P') IS NOT NULL DROP PROCEDURE sp_view_whse;");
    }
};
