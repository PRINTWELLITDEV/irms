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
        Schema::create('rslocation', function (Blueprint $table) {
            $table->string('rssite', 8);
            $table->string('rswhse', 10);
            $table->string('rsbaynum', 5);
            $table->string('rsloc', 15);
            $table->string('rsdec', 13)->nullable();
            $table->decimal('qty', 19, 8)->nullable();
            $table->dateTime('createdate')->nullable();
            $table->string('createdby', 30)->nullable();
            $table->primary(['rssite', 'rswhse', 'rsloc'], 'PK_rslocation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rslocation');
    }
};
