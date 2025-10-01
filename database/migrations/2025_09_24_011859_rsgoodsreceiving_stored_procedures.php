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
        // DB::unprepared("
        //     IF OBJECT_ID('sp_get_job_item_details', 'P') IS NOT NULL
        //         DROP PROCEDURE sp_get_job_item_details;
        // ");
        // DB::unprepared("
        //     CREATE PROCEDURE sp_get_job_item_details
        //         @rssite NVARCHAR(20),
        //         @job NVARCHAR(20) = NULL
        //     AS
        //     BEGIN
        //         SET NOCOUNT ON;

        //         DECLARE @db NVARCHAR(50);

        //         -- Map rssite to database name
        //         IF @rssite = 'PI-SP'
        //             SET @db = '[192.168.2.4].[PI-SP_App]'
        //         ELSE IF @rssite = 'FP-SP'
        //             SET @db = '[192.168.2.4].[FP-SP_App]'
        //         ELSE IF @rssite = 'PIGRP-SP'
        //             SET @db = '[192.168.2.4].[PIGRP-SP_App]'
        //         ELSE
        //             SET @db = NULL

        //         IF @db IS NOT NULL
        //         BEGIN
        //             DECLARE @sql NVARCHAR(MAX);

        //             SET @sql = '
        //                 SELECT
        //                     j.job, 
        //                     j.suffix, 
        //                     j.item, 
        //                     i.description, 
        //                     i.Uf_itemdesc_ext, 
        //                     i.u_m, 
        //                     i.Uf_Item_PalletSize
        //                 FROM [' + @db + '].dbo.job j
        //                 INNER JOIN [' + @db + '].dbo.item i ON i.item = j.item
        //                 WHERE (@job IS NULL OR j.job = @job) AND j.suffix = 0
        //             ';

        //             EXEC sp_executesql @sql, N'@job NVARCHAR(20)', @job;
        //         END
        //         ELSE
        //         BEGIN
        //             RAISERROR('Invalid site/database.', 16, 1);
        //         END
        //     END
        // ");

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
            IF OBJECT_ID('sp_goodsreceive_process', 'P') IS NOT NULL
                DROP PROCEDURE sp_goodsreceive_process;
        ");
        DB::unprepared("
			CREATE PROCEDURE sp_goodsreceive_process
                @rssite NVARCHAR(8),
                @rswhse NVARCHAR(10),
                @rsbaynum NVARCHAR(5),
                @rsloc NVARCHAR(15),
                @rslot NVARCHAR(15),
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

                DECLARE @trans_year CHAR(2) = RIGHT(CONVERT(CHAR(4), YEAR(GETDATE())), 2);
                DECLARE @last_num INT;
                DECLARE @trans_num NVARCHAR(10);

                -- Get or initialize last_num for current year
                IF EXISTS (SELECT 1 FROM rslasttran WHERE trans_year = @trans_year)
                BEGIN
                    SELECT @last_num = last_num FROM rslasttran WHERE trans_year = @trans_year;
                    SET @last_num = @last_num + 1;
                    UPDATE rslasttran SET last_num = @last_num WHERE trans_year = @trans_year;
                END
                ELSE
                BEGIN
                    SET @last_num = 1;
                    INSERT INTO rslasttran (trans_year, last_num) VALUES (@trans_year, @last_num);
                END

                -- Format trans_num as YY-0000001
                SET @trans_num = @trans_year + '-' + RIGHT('0000000' + CAST(@last_num AS VARCHAR(7)), 7);

                -- Insert into rsitemloc
                INSERT INTO rsitemloc (
                    rssite, rswhse, rsbaynum, rsloc, rspallet_num, job, item, [desc], um, qty, datercvd, createdate, createdby
                ) VALUES (
                    @rssite, @rswhse, @rsbaynum, @rsloc, @rspallet_num, @job, @item, @desc, @um, @qty, @datercvd, GETDATE(), @createdby
                );

                -- Insert into rstrans
                INSERT INTO rstrans (
                    rssite, trans_num, trxdate, trxtype, item, [desc], job, rswhse, rsloc, rslot, rspallet_num, qty, um, docnum, createdby, createdate
                ) VALUES (
                    @rssite,
                    @trans_num,
                    @datercvd,
                    'R',
                    @item,
                    @desc,
                    @job,
                    @rswhse,
                    @rsloc,
                    @rslot,
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
        // DB::unprepared("
        //     IF OBJECT_ID('sp_get_job_item_details', 'P') IS NOT NULL
        //         DROP PROCEDURE sp_get_job_item_details;
        // ");
        DB::unprepared("
            IF OBJECT_ID('sp_get_rsloc_list', 'P') IS NOT NULL
                DROP PROCEDURE sp_get_rsloc_list;
        ");
        DB::unprepared("
            IF OBJECT_ID('sp_goodsreceive_process', 'P') IS NOT NULL
                DROP PROCEDURE sp_goodsreceive_process;
        ");
    }
};
