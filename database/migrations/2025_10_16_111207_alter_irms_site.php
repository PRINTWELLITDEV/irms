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
        Schema::table('irms_site', function (Blueprint $table) {
            $table->string('site_link', 255)->nullable()->after('address');
            $table->dateTime('create_date')->nullable()->after('logo_pic_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('irms_site', function (Blueprint $table) {
            $table->dropColumn('site_link');
            $table->dropColumn('create_date');
        });
    }
};
