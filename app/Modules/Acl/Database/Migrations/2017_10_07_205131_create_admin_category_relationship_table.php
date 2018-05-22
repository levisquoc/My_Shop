<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminCategoryRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_category', function (Blueprint $table) {

            $table->foreign('admin_id')->references('id')->on('admins')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('cate_id')->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_category', function (Blueprint $table) {

        });
    }
}
