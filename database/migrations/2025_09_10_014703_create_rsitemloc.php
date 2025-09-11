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
        Schema::create('rsitemloc', function (Blueprint $table) {
            $table->string('rssite', 8);
            $table->string('rswhse', 10);
            $table->string('rsbaynum', 5)->nullable();
            $table->string('rsloc', 15);
            $table->string('rspallet_num', 10)->nullable();
            $table->string('job', 10)->nullable();
            $table->string('item', 30)->nullable();
            $table->string('desc', 60)->nullable();
            $table->string('um', 3)->nullable();
            $table->decimal('qty', 19, 8)->nullable();
            $table->dateTime('datercvd')->nullable();
            $table->dateTime('createdate')->nullable();
            $table->string('createdby', 30)->nullable();
            $table->primary(['rssite', 'rswhse', 'rsloc'], 'PK_rsitemloc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsitemloc');
    }
};
