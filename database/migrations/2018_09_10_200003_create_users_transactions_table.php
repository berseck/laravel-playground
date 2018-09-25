<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 16, 2)->default(0);
            $table->integer('from')->unsigned();
            $table->foreign('from')->references('id')->on('users');
            $table->integer('to')->unsigned();
            $table->foreign('to')->references('id')->on('users');
            $table->string('group_id', 255);
            $table->integer('type')->unsigned();
            $table->foreign('type')->references('id')->on('types');
            $table->integer('flag')->unsigned();
            $table->foreign('flag')->references('id')->on('flags');
            $table->integer('unseen')->default(1);
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users_transactions');
        Schema::enableForeignKeyConstraints();
    }
}
