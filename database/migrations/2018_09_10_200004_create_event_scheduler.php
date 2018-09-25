<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventScheduler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            SET GLOBAL event_scheduler = ON;
            CREATE EVENT currency_updater
            ON SCHEDULE EVERY 30 MINUTE
            STARTS NOW()
            DO
            UPDATE users_vcs set amount = amount + 0.25;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP EVENT currency_updater');
    }
}
