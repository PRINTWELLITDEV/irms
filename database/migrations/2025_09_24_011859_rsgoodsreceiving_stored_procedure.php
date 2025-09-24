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
        DB::unprepared("
            IF OBJECT_ID('sp_get_job_item_details', 'P') IS NOT NULL
                DROP PROCEDURE sp_get_job_item_details;
        ");
        DB::unprepared("
            CREATE PROCEDURE sp_get_job_item_details
                @rssite NVARCHAR(20),
                @job NVARCHAR(20) = NULL
            AS
            BEGIN
                SET NOCOUNT ON;

                DECLARE @db NVARCHAR(50);

                -- Map rssite to database name
                IF @rssite = 'PI-SP'
                    SET @db = 'PI-SP_App';
                ELSE IF @rssite = 'FP-SP'
                    SET @db = 'FP-SP_App';
                ELSE IF @rssite = 'PIGRP-SP'
                    SET @db = 'PIGRP-SP_App';
                ELSE
                    SET @db = NULL;

                IF @db IS NOT NULL
                BEGIN
                    DECLARE @sql NVARCHAR(MAX);

                    SET @sql = '
                        SELECT
                            j.job, 
                            j.suffix, 
                            j.item, 
                            i.description, 
                            i.Uf_itemdesc_ext, 
                            i.u_m, 
                            i.Uf_Item_PalletSize
                        FROM [' + @db + '].dbo.job j
                        INNER JOIN [' + @db + '].dbo.item i ON i.item = j.item
                        WHERE 
                          j.job = @job
                          AND j.suffix = 0
                    ';

                    EXEC sp_executesql @sql, N'@job NVARCHAR(20)', @job;
                END
                ELSE
                BEGIN
                    RAISERROR('Invalid site/database.', 16, 1);
                END
            END
        ");

        DB::unprepared("
            IF OBJECT_ID('sp_get_rsloc_list', 'P') IS NOT NULL
                DROP PROCEDURE sp_get_rsloc_list;
        ");
        DB::unprepared("
            CREATE PROCEDURE sp_get_rsloc_list
                @rssite NVARCHAR(8),
                @rswhse NVARCHAR(10),
                @rsbaynum NVARCHAR(5)
            AS
            BEGIN
                SET NOCOUNT ON;

                SELECT
                    l.rssite,
                    l.rswhse,
                    w.name AS rswhse_name, 
                    l.rsbaynum,
                    l.rsloc,
                    l.qty AS qty_onHand,
                    w.addr
                FROM
                    rslocation l
                    INNER JOIN rswhse w ON w.rssite = l.rssite AND w.rswhse = l.rswhse
                    INNER JOIN rsbayloc b ON b.rssite = l.rssite AND b.rsbaynum = l.rsbaynum
                WHERE
                    l.rssite = @rssite
                    AND l.rswhse = @rswhse
                    AND l.rsbaynum = @rsbaynum
            END
        ");
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
            IF OBJECT_ID('sp_get_job_item_details', 'P') IS NOT NULL
                DROP PROCEDURE sp_get_job_item_details;
        ");
        DB::unprepared("
            IF OBJECT_ID('sp_get_rsloc_list', 'P') IS NOT NULL
                DROP PROCEDURE sp_get_rsloc_list;
        ");
    }
};
