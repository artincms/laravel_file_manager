<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLFMFileable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lfm_fileables', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('file_id')->unsigned();
            $table->integer('fileable_id')->unsigned();
            $table->string('fileable_type', 255)->nullable()->default(null);
            $table->string('type', 255)->nullable()->default(null);
            $table->integer('created_by')->unsigned()->nullable()->default(null);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lfm_fileables');
    }
}
