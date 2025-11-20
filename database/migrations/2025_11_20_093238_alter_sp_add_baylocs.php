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
        //
        DB::unprepared("IF OBJECT_ID('sp_add_baylocs', 'P') IS NOT NULL DROP PROCEDURE sp_add_baylocs");
        // Create sp_add_baylocs
        DB::unprepared('
            CREATE PROCEDURE sp_add_baylocs
                @rssite NVARCHAR(8),
                @rswhse NVARCHAR(10),
                @rsbaynum NVARCHAR(5),
                @createdate DATETIME,
                @createdby NVARCHAR(30)
            AS
            INSERT INTO rsbayloc (rssite, rswhse, rsbaynum, createdate, createdby)
            VALUES (@rssite, @rswhse, @rsbaynum, @createdate, @createdby);
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::unprepared("IF OBJECT_ID('sp_add_baylocs', 'P') IS NOT NULL DROP PROCEDURE sp_add_baylocs");
    }
};
