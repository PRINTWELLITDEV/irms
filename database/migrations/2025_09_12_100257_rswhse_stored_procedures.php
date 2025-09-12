<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE PROCEDURE sp_add_whse
                @p_rssite VARCHAR(8),
                @p_rswhse VARCHAR(10),
                @p_name VARCHAR(30),
                @p_addr VARCHAR(60),
                @p_createdby VARCHAR(30)
            AS
            BEGIN
                INSERT INTO rswhse (rssite, rswhse, name, addr, createdate, createdby)
                VALUES (
                    @p_rssite,
                    @p_rswhse,
                    @p_name,
                    @p_addr,
                    GETDATE(),
                    @p_createdby
                );
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE sp_view_whse
            AS
            BEGIN
                SELECT rssite, rswhse, name, addr FROM rswhse
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_add_whse');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_view_whse');
    }
};
