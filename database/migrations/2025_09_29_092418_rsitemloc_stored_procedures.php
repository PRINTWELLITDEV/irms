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
                @rssite NVARCHAR(10) = NULL,
				@job NVARCHAR (15) = NULL
            AS
            SELECT
				i.rssite,
				i.rswhse,
				i.rsbaynum,
				i.job,
				i.item,
				i.[desc],
				SUM(i.qty) AS totalqty,
				i.um,
				s.rssite_desc
			FROM
				rsitemloc AS i
				INNER JOIN irms_site AS s ON s.rssite = i.rssite
			WHERE
				(@job IS NULL OR i.job = @job)
				AND (@rssite IS NULL OR i.rssite = @rssite)
			GROUP BY
				i.rssite,
				i.rswhse,
				i.rsbaynum,
				i.job,
				i.item,
				i.[desc],
				i.um,
				s.rssite_desc
			ORDER BY
				i.job;
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
