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
        DB::unprepared('
            CREATE PROCEDURE sp_job_details
                @job NVARCHAR(50),
                @rssite NVARCHAR(8) = NULL
            AS
            BEGIN
                SET NOCOUNT ON;

                SELECT
                    i.rssite,
                    rspallet_num,
                    job,
                    rsloc,
                    qty,
                    um,
                    datercvd,
                    createdby AS rcvd_by_id,
                    u.name AS rcvd_by_name,
                    s.rssite_desc
                FROM
                    rsitemloc i
                    INNER JOIN irms_site s ON s.rssite = i.rssite
					INNER JOIN rsusers u ON u.userid = i.createdby
                WHERE
                    job = @job
                    AND (@rssite IS NULL OR i.rssite = @rssite)
                ORDER BY
                    job, rsloc;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("IF OBJECT_ID('sp_job_details', 'P') IS NOT NULL DROP PROCEDURE sp_job_details;");
    }
};
