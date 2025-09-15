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
        DB::unprepared("IF OBJECT_ID('sp_view_bayloc', 'P') IS NOT NULL DROP PROCEDURE sp_view_bayloc");
        DB::unprepared("IF OBJECT_ID('sp_add_bayloc', 'P') IS NOT NULL DROP PROCEDURE sp_add_bayloc");

        // Create sp_view_bayloc
        DB::unprepared('
            CREATE PROCEDURE sp_view_bayloc
            AS
            SELECT b.rssite, b.rsbaynum, b.createdate, b.createdby, s.logo_pic_url
            FROM rsbayloc b
			INNER JOIN irms_site s ON s.rssite = b.rssite
            ;
        ');

        // Create sp_add_bayloc
        DB::unprepared('
            CREATE PROCEDURE sp_add_bayloc
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
        DB::unprepared("IF OBJECT_ID('sp_view_bayloc', 'P') IS NOT NULL DROP PROCEDURE sp_view_bayloc");
        DB::unprepared("IF OBJECT_ID('sp_add_bayloc', 'P') IS NOT NULL DROP PROCEDURE sp_add_bayloc");
    }
};
