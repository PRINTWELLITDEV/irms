<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // irms_site table
        Schema::create('irms_site', function (Blueprint $table) {
            $table->string('rssite', 8)->primary();
            $table->string('rssite_desc', 50);
            $table->text('address')->nullable();
        });

        // rsusers table
        Schema::create('rsusers', function (Blueprint $table) {
            $table->string('rssite', 8);
            $table->string('userid', 8);
            $table->string('name')->nullable();
            $table->string('password', 255);
            $table->string('email', 255);
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('level')->default(1);
            $table->dateTime('create_date')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->string('updated_by', 8)->nullable();
            $table->string('updated_by_sql', 128)->nullable();
            $table->string('gender', 10)->nullable();
            $table->text('profile_pic_url')->nullable();
            $table->rememberToken(); // varchar(100) nullable

            $table->primary(['rssite', 'userid']);
        });
        // Trigger to auto-update updated_date and updated_by on rsusers table
        DB::unprepared('
            CREATE TRIGGER trg_rsusers_update
            ON dbo.rsusers
            AFTER UPDATE
            AS
            BEGIN
                SET NOCOUNT ON;
                UPDATE u
                SET
                    updated_date = GETDATE(),
                    updated_by_sql = SUSER_SNAME()
                FROM rsusers u
                INNER JOIN inserted i
                    ON u.rssite = i.rssite
                AND u.userid = i.userid;
            END
        ');



        // sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 255);
            $table->string('rssite', 8)->nullable();
            // $table->string('rsuserid', 8)->nullable();
            $table->string('user_id', 8)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity');

            $table->primary('id');

            // composite FK (rssite + user_id) → (rssite + userid)
            // $table->foreign(['rssite', 'user_id'])
            //       ->references(['rssite', 'userid'])
            //       ->on('rsusers')
            //       ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('rsusers');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_rsusers_update');
        Schema::dropIfExists('irms_site');
    }
};
