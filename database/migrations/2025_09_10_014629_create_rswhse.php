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
        Schema::create('rswhse', function (Blueprint $table) {
            $table->string('rssite', 8);
            $table->string('rswhse', 10);
            $table->string('name', 30);
            $table->string('addr', 60)->nullable();
            $table->dateTime('createdate')->nullable();
            $table->string('createdby', 30);
            $table->primary(['rssite', 'rswhse'], 'PK_whse');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rswhse');
    }
};
