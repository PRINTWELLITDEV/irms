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
    }
};
