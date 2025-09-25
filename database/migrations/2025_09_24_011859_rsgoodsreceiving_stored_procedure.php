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
                @rsbaynum NVARCHAR(5),
                @item NVARCHAR(30),
                @pallet_size DECIMAL(19,8)
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
                    AND l.qty < @pallet_size
            END
        ");
        
        DB::unprepared("
            IF OBJECT_ID('sp_goodsreceived_process', 'P') IS NOT NULL
                DROP PROCEDURE sp_goodsreceived_process;
        ");
        DB::unprepared("
			CREATE PROCEDURE sp_goodsreceived_process
                @rssite NVARCHAR(8),
                @rswhse NVARCHAR(10),
                @rsbaynum NVARCHAR(5),
                @rsloc NVARCHAR(15),
                @rspallet_num NVARCHAR(10),
                @job NVARCHAR(10),
                @item NVARCHAR(30),
                @desc NVARCHAR(60),
                @um NVARCHAR(3),
                @qty DECIMAL(19,8),
                @datercvd DATETIME,
                @docnum NVARCHAR(20),
                @createdby NVARCHAR(30)
            AS
            BEGIN
                SET NOCOUNT ON;

                -- Insert into rsitemloc
                INSERT INTO rsitemloc (
                    rssite, rswhse, rsbaynum, rsloc, rspallet_num, job, item, [desc], um, qty, datercvd, createdate, createdby
                ) VALUES (
                    @rssite, @rswhse, @rsbaynum, @rsloc, @rspallet_num, @job, @item, @desc, @um, @qty, @datercvd, GETDATE(), @createdby
                );

                -- Insert into rstrans
                INSERT INTO rstrans (
                    rssite, trans_num, trxdate, trxtype, item, [desc], job, rswhse, rsloc, rspallet_num, qty, um, docnum, createdby, createdate
                ) VALUES (
                    @rssite,
                    (SELECT ISNULL(MAX(trans_num),0)+1 FROM rstrans WHERE rssite=@rssite), -- auto-increment per site
                    @datercvd,
                    'R', -- R for Receiving
                    @item,
                    @desc,
                    @job,
                    @rswhse,
                    @rsloc,
                    @rspallet_num,
                    @qty,
                    @um,
                    @docnum,
                    @createdby,
                    GETDATE()
                );

                -- Update rslocation qty
                UPDATE rslocation
                SET qty = ISNULL(qty,0) + @qty
                WHERE rssite = @rssite AND rswhse = @rswhse AND rsloc = @rsloc;
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
        DB::unprepared("
            IF OBJECT_ID('sp_goodsreceived_process', 'P') IS NOT NULL
                DROP PROCEDURE sp_goodsreceived_process;
        ");
    }
};
