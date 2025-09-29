<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop if exists
        \DB::unprepared("IF OBJECT_ID('sp_view_rsitemlocs', 'P') IS NOT NULL DROP PROCEDURE sp_view_rsitemlocs");

        // Create sp_view_rsitemlocs
        \DB::unprepared('
            CREATE PROCEDURE sp_view_rsitemlocs
                @rssite VARCHAR(8) = NULL
            AS
            SELECT 
                i.*,
                u.name AS createdby_name,
                s.rssite_desc, s.address, s.logo_pic_url
            FROM rsitemloc i
            INNER JOIN irms_site s ON s.rssite = i.rssite
            LEFT JOIN rsusers u ON i.createdby = u.userid
            WHERE (@rssite IS NULL OR i.rssite = @rssite)
            ORDER BY i.rssite, i.rswhse, i.rsloc
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::unprepared("IF OBJECT_ID('sp_view_rsitemlocs', 'P') IS NOT NULL DROP PROCEDURE sp_view_rsitemlocs");
    }
};
