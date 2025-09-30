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
        \DB::unprepared("
            IF OBJECT_ID('sp_get_item_in_rsloc_list', 'P') IS NOT NULL
                DROP PROCEDURE sp_get_item_in_rsloc_list;
        ");
        \DB::unprepared("
            CREATE PROCEDURE sp_get_item_in_rsloc_list
                @rssite NVARCHAR(8),
                @job NVARCHAR(10)
            AS
            BEGIN
                SET NOCOUNT ON;
                SELECT 
                    l.rsbaynum,
                    l.rsloc,
                    i.rspallet_num,
                    i.qty,
                    i.um,
                    i.datercvd
                FROM rslocation l
                INNER JOIN rsitemloc i ON l.rssite = i.rssite AND i.rsloc = l.rsloc
                WHERE l.rssite = @rssite
                  AND i.job = @job
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::unprepared("
            IF OBJECT_ID('sp_get_item_in_rsloc_list', 'P') IS NOT NULL
                DROP PROCEDURE sp_get_item_in_rsloc_list;
        ");
    }
};
