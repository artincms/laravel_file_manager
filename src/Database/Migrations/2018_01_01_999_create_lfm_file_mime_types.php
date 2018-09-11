<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLFMFileMimeTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lfm_file_mime_types', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('name', 255)->nullable()->default(null);
            $table->string('mimeType', 255)->nullable()->default(null);
            $table->string('ext', 50)->nullable()->default(null);;
            $table->string('icon_class', 250)->nullable()->default(null);
            $table->string('description', 255)->nullable()->default(null);
            $table->integer('created_by')->unsigned()->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
       
        //\DB::unprepared(file_get_contents(__DIR__.'/sql/file_mime_type_data.sql') );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lfm_file_mime_types');
    }
}
