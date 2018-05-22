<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RssLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rss_links', function (Blueprint $table) {
            $table->increments('id');
            $table->text('rss_link');
            $table->integer('cate_id')->unsigned()->foreign()->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('page_target');
            $table->softDeletes();
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
        Schema::dropIfExists('rss_links');
    }
}