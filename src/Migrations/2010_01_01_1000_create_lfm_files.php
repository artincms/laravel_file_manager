<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLFMFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lfm_files', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('originalName', 255)->nullable()->default(null);
            $table->string('extension', 255)->nullable()->default(null);
            $table->string('mimeType', 255)->nullable()->default(null);
            $table->double('size')->nullable()->default(null);
            $table->string('path', 255)->nullable()->default(null);
            $table->string('filename', 255)->nullable()->default(null);
            $table->integer('created_by')->unsigned()->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lfm_files');
    }
}
