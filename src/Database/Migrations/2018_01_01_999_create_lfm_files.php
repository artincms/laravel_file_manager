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
            $table->integer('user_id')->unsigned();
            $table->integer('category_id');
            $table->integer('file_mime_type_id')->unsigned();
            $table->string('original_name', 255)->nullable()->default(null);
            $table->string('extension', 255)->nullable()->default(null);
            $table->string('mimeType', 255)->nullable()->default(null);
            $table->string('path', 255)->nullable()->default(null);
            $table->string('filename', 255)->nullable()->default(null);
            $table->string('file_md5' ,255)->nullable()->default(null);
            $table->string('large_filename' ,255)->nullable()->default(null);
            $table->enum('is_direct',['1',0])->default(0);
            $table->string('medium_filename' ,255)->nullable()->default(null);
            $table->string('small_filename' ,255)->nullable()->default(null);
            $table->integer('version')->unsigned()->default(0);
            $table->integer('large_version')->unsigned()->default(0);
            $table->integer('medium_version')->unsigned()->default(0);
            $table->integer('small_version')->unsigned()->default(0);
            $table->double('size')->nullable()->default(null);
            $table->integer('large_size')->unsigned()->default(0);
            $table->integer('medium_size')->unsigned()->default(0);
            $table->integer('small_size')->unsigned()->default(0);
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
