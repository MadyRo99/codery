<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->bigInteger('author_id')->unsigned();
            $table->bigInteger('article_category')->unsigned();
            $table->text('content');
            $table->integer('est_time');
            $table->string('slug');
            $table->string('main_image')->nullable();
            $table->integer('status')->default(0);
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('article_category')->references('id')->on('article_categories');
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
        Schema::dropIfExists('articles');
    }
}