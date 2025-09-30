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
        \DB::unprepared("IF OBJECT_ID('sp_view_rslocs', 'P') IS NOT NULL DROP PROCEDURE sp_view_rslocs");
        \DB::unprepared("IF OBJECT_ID('sp_add_rslocs', 'P') IS NOT NULL DROP PROCEDURE sp_add_rslocs");

        // Create sp_view_rslocs
        \DB::unprepared('
            CREATE PROCEDURE sp_view_rslocs
                @rssite VARCHAR(8) = NULL
            AS
            SELECT 
                l.*,
                u.name AS createdby_name,
                s.rssite_desc, s.address, logo_pic_url
            FROM rslocation l
            INNER JOIN irms_site s ON s.rssite = l.rssite
            LEFT JOIN rsusers u ON l.createdby = u.userid
            WHERE (@rssite IS NULL OR l.rssite = @rssite)
            ORDER BY l.rssite, l.rswhse, l.rsloc
        ');

        // Create sp_add_rslocs
        \DB::unprepared('
            CREATE PROCEDURE sp_add_rslocs
                @rssite NVARCHAR(8),
                @rswhse NVARCHAR(10),
                @rsbaynum NVARCHAR(5),
                @rsloc NVARCHAR(15),
                @rsdesc NVARCHAR(13),
                @qty DECIMAL(19,8),
                @createdate DATETIME,
                @createdby NVARCHAR(30)
            AS
            INSERT INTO rslocation (rssite, rswhse, rsbaynum, rsloc, rsdesc, qty, createdate, createdby)
            VALUES (@rssite, @rswhse, @rsbaynum, @rsloc, @rsdesc, @qty, @createdate, @createdby);
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::unprepared("IF OBJECT_ID('sp_view_rslocs', 'P') IS NOT NULL DROP PROCEDURE sp_view_rslocs");
        \DB::unprepared("IF OBJECT_ID('sp_add_rslocs', 'P') IS NOT NULL DROP PROCEDURE sp_add_rslocs");
    }
};
