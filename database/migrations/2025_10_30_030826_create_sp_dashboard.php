<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations (Create BOTH Stored Procedures).
     */
    public function up(): void
    {
        // 1. Goods Dispatched Count (Today)
        DB::unprepared('
            CREATE PROCEDURE sp_GetGoodsDispatchedCount(@UserId VARCHAR(50))
                AS
                    BEGIN
                        SELECT COUNT(*) AS GoodsDispatchedCount
                        FROM rstrans t
                        INNER JOIN rsusers u ON t.createdby = u.userid
                        WHERE u.userid = @UserId
                        AND t.trxtype = \'D\'
                        AND CAST(t.trxdate AS DATE) = CAST(GETDATE() AS DATE);
                    END
        ');

        // 2. Goods Received Count (Today)
        DB::unprepared('
        CREATE PROCEDURE sp_GetGoodsReceivedCount(@UserId VARCHAR(50))
            AS
                BEGIN
                    SELECT COUNT(*) AS GoodsReceivedCount
                    FROM rstrans t
                    INNER JOIN rsusers u ON t.createdby = u.userid
                    WHERE u.userid = @UserId
                    AND t.trxtype = \'R\'
                    AND CAST(t.trxdate AS DATE) = CAST(GETDATE() AS DATE);
                END
        ');

        // 3. Warehouse Vacancy
        DB::unprepared('
        CREATE PROCEDURE sp_GetWarehouseOccupancy(@site VARCHAR(10))
        AS
        BEGIN
            SELECT
                ROUND(
                    (
                        CAST(SUM(CASE WHEN qty = 0 THEN 1 ELSE 0 END) AS FLOAT)
                        /
                        NULLIF(COUNT(*), 0)
                    ) * 100,
                    2
                ) AS VacantRackPercentage
            FROM rslocation
            -- Filters the table rows ONCE based on the input parameter
            WHERE rssite = @site;
        END
        ');

        // 4. Percentage of Received Goods by the user input
        DB::unprepared('
        CREATE PROCEDURE sp_GetPercentageReceivedGoods(@site VARCHAR(10), @UserId VARCHAR(50))
        AS
        BEGIN
            SELECT
                ROUND(
                    (
                        CAST(
                            (SELECT COUNT(*) FROM rstrans t
                            INNER JOIN rslocation l ON t.rsloc = l.rsloc
                            WHERE t.trxtype = \'R\' AND t.rssite = @site AND t.createdby = @UserId)
                        AS FLOAT)
                        /
                        NULLIF(
                            (SELECT COUNT(*) FROM rstrans t
                            INNER JOIN rslocation l ON t.rsloc = l.rsloc
                            WHERE t.rssite = @site AND t.createdby = @UserId),
                        0)
                    ) * 100,
                2) AS PercentageReceivedGoods;
        END
        ');


        // 5. Percentage of Dispatched Goods by the user input
        DB::unprepared('
        CREATE PROCEDURE sp_GetPercentageDispatchedGoods(@site VARCHAR(10), @UserId VARCHAR(50))
        AS
        BEGIN
            SELECT
                ROUND(
                    (
                        CAST(
                            (SELECT COUNT(*) FROM rstrans t
                            INNER JOIN rslocation l ON t.rsloc = l.rsloc
                            WHERE t.trxtype = \'D\' AND t.rssite = @site AND t.createdby = @UserId)
                        AS FLOAT)
                        /
                        NULLIF(
                            (SELECT COUNT(*) FROM rstrans t
                            INNER JOIN rslocation l ON t.rsloc = l.rsloc
                            WHERE t.rssite = @site AND t.createdby = @UserId),
                        0)
                    ) * 100,
                2) AS PercentageDispatchedGoods;
        END
        ');

    }
    /**
     * Reverse the migrations (Drop BOTH Stored Procedures).
     */
    public function down(): void
    {
        // Drop Dispatched SP
        DB::unprepared('
            IF EXISTS (SELECT * FROM sys.objects WHERE type = \'P\' AND name = \'sp_GetGoodsDispatchedCount\')
            BEGIN
                DROP PROCEDURE sp_GetGoodsDispatchedCount;
            END
        ');

        // Drop Received SP
        DB::unprepared('
            IF EXISTS (SELECT * FROM sys.objects WHERE type = \'P\' AND name = \'sp_GetGoodsReceivedCount\')
            BEGIN
                DROP PROCEDURE sp_GetGoodsReceivedCount;
            END
        ');

        DB::unprepared('
        IF EXISTS (SELECT * FROM sys.objects WHERE type = \'P\' AND name = \'sp_GetWarehouseOccupancy\')
        BEGIN
            DROP PROCEDURE sp_GetWarehouseOccupancy;
        END
        ');

        DB::unprepared('
            IF EXISTS (SELECT * FROM sys.objects WHERE type = \'P\' AND name = \'sp_GetPercentageReceivedGoods\')
            BEGIN
                DROP PROCEDURE sp_GetPercentageReceivedGoods;
            END
        ');
    }
};
