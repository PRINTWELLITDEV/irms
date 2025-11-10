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

        //6. Users transaction history
        DB::unprepared('
        CREATE PROCEDURE sp_GetUserTransactions(@userid VARCHAR(50))
        AS
        BEGIN
            SELECT
                t.rssite,
                t.trans_num,
                t.trxdate,
                t.trxtype,
                t.item,
                t.rsloc
            FROM
                rstrans t
            WHERE
                t.createdby = @userid
            ORDER BY
                t.trxdate DESC;
        END
        ');

        //7. Occupied Rack Percentage
        DB::unprepared('
        CREATE PROCEDURE sp_GetOccupiedRackPercentage(@rssite VARCHAR(50))
        AS
        BEGIN
            SELECT
            ROUND(
                (
                    CAST(SUM(CASE WHEN qty > 0 THEN 1 ELSE 0 END) AS FLOAT)

                    /

                    NULLIF(COUNT(*), 0)
                ) * 100,
                2
            ) AS OccupiedRackPercentage
            FROM rslocation
            WHERE rssite = @rssite;
        END
        ');

        //8. Total Receiving & Dispatching per Week
        DB::unprepared('
        CREATE PROCEDURE sp_get_weekly_transaction_count(@userid VARCHAR(50), @rssite VARCHAR(50))
        AS
        BEGIN
            SET NOCOUNT ON;
            SELECT
                CAST(t.trxdate AS DATE) AS transaction_day,
                COUNT(*) AS total_transaction_count
            FROM rstrans t
            WHERE t.trxdate >= DATEADD(day, -7, GETDATE()) 
            AND t.createdby = @userid
            AND t.rssite = @rssite
            GROUP BY CAST(t.trxdate AS DATE)
            ORDER BY transaction_day
        END
        ');


        //Admin - Supervisor/Manager

        DB::unprepared('
            CREATE PROCEDURE sp_GetGoodsMovementToday(@rssite VARCHAR(50))
            AS 
            BEGIN
                SELECT 
                COUNT(*)
                FROM rstrans t
                WHERE t.rssite = @rssite AND CAST(t.trxdate AS DATE) = CAST(GETDATE() AS DATE);
            END
        ');


        //Superadmin

        // 9. Total Warehouse of all sites
        DB::unprepared('
            CREATE PROCEDURE sp_GetTotalWarehouse
            AS
            BEGIN 
                SELECT COUNT(*) as totalWarehouse
                FROM rswhse
            END
        ');


        DB::unprepared('
            CREATE PROCEDURE sp_GetPIWarehouse
            AS
            BEGIN 
                SELECT COUNT(*) as totalPIWarehouse
                FROM rswhse r
                WHERE r.rssite = \'PI-SP\'
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE sp_GetFPCWarehouse
            AS
            BEGIN 
                SELECT COUNT(*) as totalFPCWarehouse
                FROM rswhse r
                WHERE r.rssite = \'FP-SP\'
            END
        ');

        DB::unprepared('
            CREATE PROCEDURE sp_GetPWPCWarehouse
            AS
            BEGIN 
                SELECT COUNT(*) as totalPWPCWarehouse
                FROM rswhse r
                WHERE r.rssite = \'PIGRP-SP\'
            END
        ');

    }


    //SUPER ADMIN


    /**
     * Reverse the migrations (Drop BOTH Stored Procedures).    
     */
   /**
     * Reverse the migrations (Drop ALL Stored Procedures).      
     */
    public function down(): void
    {
        // Define all stored procedures to be dropped
        $proceduresToDrop = [
            'sp_GetGoodsDispatchedCount',
            'sp_GetGoodsReceivedCount',
            'sp_GetWarehouseOccupancy',
            'sp_GetPercentageReceivedGoods',
            'sp_GetPercentageDispatchedGoods', // Was missing
            'sp_GetUserTransactions',
            'sp_GetOccupiedRackPercentage',
            'sp_get_weekly_transaction_count', // Was missing
            'sp_GetGoodsMovementToday',        // Was missing
            'sp_GetTotalWarehouse',            // Was missing
            'sp_GetPIWarehouse',               // Was missing
            'sp_GetFPCWarehouse',              // Was missing
            'sp_GetPWPCWarehouse',             // Was missing
        ];

        foreach ($proceduresToDrop as $procedure) {
            DB::unprepared("
                IF EXISTS (SELECT * FROM sys.objects WHERE type = 'P' AND name = '{$procedure}')
                BEGIN
                    DROP PROCEDURE {$procedure};
                END
            ");
        }
    }
};
