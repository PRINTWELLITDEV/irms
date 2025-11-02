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

        // Create sp_rack_map
        \DB::unprepared('
            CREATE PROCEDURE sp_rack_map
                @rssite NVARCHAR(8),
                @rswhse NVARCHAR(10),
                @rsbaynum NVARCHAR(5)
            AS
            BEGIN
                SELECT *
                FROM rslocation
                WHERE rssite = @rssite
                  AND rswhse = @rswhse
                  AND rsbaynum = @rsbaynum
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::unprepared("IF OBJECT_ID('sp_rack_map', 'P') IS NOT NULL DROP PROCEDURE sp_rack_map");
    }
};
