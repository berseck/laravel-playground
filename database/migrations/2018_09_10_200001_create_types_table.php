<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesTable extends Migration
{

    protected $contents = array(
        ['name'=>"credit"],
        ['name'=>"debit"],
        ['name'=>"refund"],
    );

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        DB::table('types')->insert($this->contents);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types');
    }
}
