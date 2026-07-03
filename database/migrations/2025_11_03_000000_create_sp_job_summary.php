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
        \DB::unprepared("IF OBJECT_ID('sp_job_summary', 'P') IS NOT NULL DROP PROCEDURE sp_job_summary");

        // Create sp_job_summary
        \DB::unprepared('
            CREATE PROCEDURE sp_job_summary
                @job NVARCHAR(50) = NULL,
                @rssite NVARCHAR(8) = NULL
            AS
            BEGIN
                SET NOCOUNT ON;

                SELECT
                    i.rssite,
                    i.rswhse,
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
                    i.job,
                    i.item,
                    i.[desc],
                    i.um,
                    s.rssite_desc
                ORDER BY
                    i.job;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::unprepared("IF OBJECT_ID('sp_job_summary', 'P') IS NOT NULL DROP PROCEDURE sp_job_summary");
    }
};
