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
        Schema::create('rstrans', function (Blueprint $table) {
            $table->string('rssite', 8);
            $table->integer('trans_num');
            $table->dateTime('trxdate')->nullable();
            $table->string('item', 30)->nullable();
            $table->string('desc', 60)->nullable();
            $table->string('job', 10)->nullable();
            $table->string('rswhse', 10)->nullable();
            $table->string('rsloc', 15)->nullable();
            $table->string('rslot', 15)->nullable();
            $table->string('rspallet_num', 10)->nullable();
            $table->decimal('qty', 19, 8)->nullable();
            $table->string('um', 3)->nullable();
            $table->string('docnum', 20)->nullable();
            $table->string('createdby', 30)->nullable();
            $table->dateTime('createdate')->nullable();
            $table->primary(['rssite', 'trans_num'], 'PK_rstrans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rstrans');
    }
};
