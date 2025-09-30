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
        \DB::unprepared("IF OBJECT_ID('sp_view_rstrans', 'P') IS NOT NULL DROP PROCEDURE sp_view_rstrans");

        // Create sp_view_rstrans
        \DB::unprepared('
            CREATE PROCEDURE sp_view_rstrans
                @rssite VARCHAR(8) = NULL
            AS
            SELECT
                s.rssite_desc,
                t.*,
                u.name,
                u.department,
                u.section,
                u.position
            FROM rstrans t
            INNER JOIN irms_site s ON s.rssite = t.rssite
            INNER JOIN rsusers u ON u.userid = t.createdby
            WHERE (@rssite IS NULL OR t.rssite = @rssite)
            ORDER BY t.trans_num DESC, t.trxdate DESC
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::unprepared("IF OBJECT_ID('sp_view_rstrans', 'P') IS NOT NULL DROP PROCEDURE sp_view_rstrans");
    }
};
