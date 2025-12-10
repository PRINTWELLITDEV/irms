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
        \DB::unprepared("IF OBJECT_ID('sp_rack_map', 'P') IS NOT NULL DROP PROCEDURE sp_rack_map");

        // Create sp_rack_map with job details
        \DB::unprepared('
            CREATE PROCEDURE sp_rack_map
                @rssite NVARCHAR(8),
                @rswhse NVARCHAR(10),
                @rsbaynum NVARCHAR(5)
            AS
            BEGIN
                SELECT 
                    rl.rssite,
                    rl.rswhse,
                    rl.rsbaynum,
                    rl.rsloc,
                    rl.rsdesc,
                    rl.qty,
                    rl.createdate,
                    ril.job,
                    ril.item,
                    ril.[desc],
                    ril.rspallet_num
                FROM rslocation rl
                LEFT JOIN rsitemloc ril ON ril.rsloc = rl.rsloc AND ril.rssite = rl.rssite
                WHERE rl.rssite = @rssite
                    AND rl.rswhse = @rswhse
                    AND rl.rsbaynum = @rsbaynum
                ORDER BY rl.rsloc
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        \DB::unprepared("IF OBJECT_ID('sp_rack_map', 'P') IS NOT NULL DROP PROCEDURE sp_rack_map");
    }
};
