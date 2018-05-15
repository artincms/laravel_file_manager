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
            $table->integer('category_id')->unsigned();
            $table->integer('file_mime_type_id')->unsigned();
            $table->string('originalName', 255)->nullable()->default(null);
            $table->string('extension', 255)->nullable()->default(null);
            $table->string('mimeType', 255)->nullable()->default(null);
            $table->double('size')->nullable()->default(null);
            $table->string('path', 255)->nullable()->default(null);
            $table->string('filename', 255)->nullable()->default(null);
            $table->string('file_md5' ,255)->nullable()->default(null);
            $table->string('large_filename' ,255)->nullable()->default(null);
            $table->string('medium_filename' ,255)->nullable()->default(null);
            $table->string('small_filename' ,255)->nullable()->default(null);
            $table->integer('version')->unsigned();
            $table->integer('large_version')->unsigned();
            $table->integer('medium_version')->unsigned();
            $table->integer('small_version')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->integer('created_by')->unsigned()->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('lfm_files', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('lfm_files', function (Blueprint $table) {
            $table->foreign('file_mime_type_id')->references('id')->on('lfm_file_mime_types')->onDelete('cascade');
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
