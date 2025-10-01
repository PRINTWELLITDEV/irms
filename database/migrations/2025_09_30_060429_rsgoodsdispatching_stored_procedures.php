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

        \DB::unprepared("
            IF OBJECT_ID('sp_goodsdispatch_process', 'P') IS NOT NULL
                DROP PROCEDURE sp_goodsdispatch_process;
        ");
        \DB::unprepared("
            CREATE PROCEDURE sp_goodsdispatch_process
                @rssite NVARCHAR(8),
                @rswhse NVARCHAR(10),
                @rsloc NVARCHAR(15),
                @rslot NVARCHAR(15),
                @rspallet_num NVARCHAR(10),
                @job NVARCHAR(10),
                @item NVARCHAR(30),
                @desc NVARCHAR(60),
                @um NVARCHAR(3),
                @qty DECIMAL(19,8),
                @datedispatch DATETIME,
                @docnum NVARCHAR(20),
                @createdby NVARCHAR(30)
            AS
            BEGIN
                SET NOCOUNT ON;

                DECLARE @rsitemloc_qty DECIMAL(19,8);

                SELECT @rsitemloc_qty = qty
                FROM rsitemloc
                WHERE rssite = @rssite AND rswhse = @rswhse AND rsloc = @rsloc AND job = @job AND item = @item;

                IF @rsitemloc_qty IS NULL
                BEGIN
                    RAISERROR('Item location not found.', 16, 1);
                    RETURN;
                END

                IF @qty < @rsitemloc_qty
                BEGIN
                    UPDATE rslocation
                    SET qty = ISNULL(qty,0) - @qty
                    WHERE rssite = @rssite AND rswhse = @rswhse AND rsloc = @rsloc;

                    UPDATE rsitemloc
                    SET qty = qty - @qty
                    WHERE rssite = @rssite AND rswhse = @rswhse AND rsloc = @rsloc AND job = @job AND item = @item;
                END
                ELSE IF @qty = @rsitemloc_qty
                BEGIN
                    UPDATE rslocation
                    SET qty = ISNULL(qty,0) - @qty
                    WHERE rssite = @rssite AND rswhse = @rswhse AND rsloc = @rsloc;

                    DELETE FROM rsitemloc
                    WHERE rssite = @rssite AND rswhse = @rswhse AND rsloc = @rsloc AND job = @job AND item = @item;
                END
                ELSE
                BEGIN
                    RAISERROR('Dispatch quantity exceeds available item location quantity.', 16, 1);
                    RETURN;
                END

                DECLARE @trans_year CHAR(2) = RIGHT(CONVERT(CHAR(4), YEAR(GETDATE())), 2);
                DECLARE @last_num INT;
                DECLARE @trans_num NVARCHAR(10);

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

                SET @trans_num = @trans_year + '-' + RIGHT('0000000' + CAST(@last_num AS VARCHAR(7)), 7);

                INSERT INTO rstrans (
                    rssite, trans_num, trxdate, trxtype, item, [desc], job, rswhse, rsloc, rslot, rspallet_num, qty, um, docnum, createdby, createdate
                ) VALUES (
                    @rssite,
                    @trans_num,
                    @datedispatch,
                    'D',
                    @item,
                    @desc,
                    @job,
                    @rswhse,
                    @rsloc,
                    @rslot,
                    @rspallet_num,
                    -@qty,
                    @um,
                    @docnum,
                    @createdby,
                    GETDATE()
                );
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
        \DB::unprepared("
            IF OBJECT_ID('sp_goodsdispatch_process', 'P') IS NOT NULL
                DROP PROCEDURE sp_goodsdispatch_process;
        ");
    }
};
