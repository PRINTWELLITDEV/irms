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
                i.rssite,
                rswhse,
                rsbaynum,
                job,
                item,
                [desc],
                SUM(qty) AS [totalqty],
                um,
                s.rssite_desc
            FROM
                rsitemloc i
                INNER JOIN irms_site s ON s.rssite = i.rssite
            WHERE
                job IS NOT NULL
				AND (@rssite IS NULL OR i.rssite = @rssite)
            GROUP BY
                i.rssite,
                rswhse,
                rsbaynum,
                job,
                item,
                [desc],
                um,
                s.rssite_desc
            ORDER BY
                job;
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
