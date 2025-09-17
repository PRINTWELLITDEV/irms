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
        // Drop if exists
        DB::unprepared("IF OBJECT_ID('sp_view_baylocs', 'P') IS NOT NULL DROP PROCEDURE sp_view_baylocs");
        DB::unprepared("IF OBJECT_ID('sp_add_baylocs', 'P') IS NOT NULL DROP PROCEDURE sp_add_baylocs");

        // Create sp_view_baylocs
        DB::unprepared('
            CREATE PROCEDURE sp_view_baylocs
            AS
            SELECT 
            b.*,
            u.name,
            s.rssite_desc,
            s.logo_pic_url
            FROM rsbayloc b
            INNER JOIN irms_site s ON s.rssite = b.rssite
            LEFT JOIN rsusers u ON b.createdby = u.userid
            ORDER BY b.rssite, b.rsbaynum
            ;
        ');

        // Create sp_add_baylocs
        DB::unprepared('
            CREATE PROCEDURE sp_add_baylocs
                @rssite NVARCHAR(8),
                @rsbaynum NVARCHAR(5),
                @createdate DATETIME,
                @createdby NVARCHAR(30)
            AS
            INSERT INTO rsbayloc (rssite, rsbaynum, createdate, createdby)
            VALUES (@rssite, @rsbaynum, @createdate, @createdby);
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("IF OBJECT_ID('sp_view_baylocs', 'P') IS NOT NULL DROP PROCEDURE sp_view_baylocs");
        DB::unprepared("IF OBJECT_ID('sp_add_baylocs', 'P') IS NOT NULL DROP PROCEDURE sp_add_baylocs");
    }
};
