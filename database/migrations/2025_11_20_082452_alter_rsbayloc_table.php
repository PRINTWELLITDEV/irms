<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rsbayloc', function (Blueprint $table) {
            // Drop the existing primary key
            $table->dropPrimary('PK_rsbayloc');
            
            // Add rswhse column after rssite as nullable first
            $table->string('rswhse', 10)->nullable()->after('rssite');
        });

        // Update existing records with default warehouse values based on site
        DB::statement("
            UPDATE rsbayloc 
            SET rswhse = CASE 
                WHEN rssite = 'FP-SP' THEN 'FBIC-BLDG8'
                WHEN rssite = 'PI-SP' THEN 'PBIC-BLDG1'
                WHEN rssite = 'PIGRP-SP' THEN 'PGBIC-BL1'
                ELSE 'DEFAULT'
            END
        ");

        Schema::table('rsbayloc', function (Blueprint $table) {
            // Make rswhse NOT NULL after updating values
            $table->string('rswhse', 10)->nullable(false)->change();
            
            // Create new composite primary key
            $table->primary(['rssite', 'rswhse', 'rsbaynum'], 'PK_rsbayloc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rsbayloc', function (Blueprint $table) {
            // Drop the current primary key
            $table->dropPrimary('PK_rsbayloc');
            
            // Drop rswhse column
            $table->dropColumn('rswhse');
            
            // Restore original primary key
            $table->primary(['rssite', 'rsbaynum'], 'PK_rsbayloc');
        });
    }
};
