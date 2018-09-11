<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLfmCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lfm_categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable()->unsigned()->default(0);
            $table->string('title')->nullable()->default(null);;
            $table->longText('title_disc')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->string('parent_category_id',255)->nullable()->default(null);
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
        Schema::dropIfExists('lfm_categories');
    }
}
