<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlagsTable extends Migration
{

    protected $contents = array(
        ['name'=>"success"],
        ['name'=>"fail"],
        ['name'=>"declined"],
        ['name'=>"malicious"],
    );

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        DB::table('flags')->insert($this->contents);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flags');
    }
}
