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
        Schema::create('rsbayloc', function (Blueprint $table) {
            $table->string('rssite', 8);
            $table->string('rsbaynum', 5);
            $table->dateTime('createdate');
            $table->string('createdby', 30)->nullable();
            $table->primary(['rssite', 'rsbaynum'], 'PK_rsbayloc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsbayloc');
    }
};
