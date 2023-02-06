<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_news', function (Blueprint $table) {
            $table->id('id');
            $table->string('source',80);
            $table->string('author',100);
            $table->string('title',150)->unique();
            $table->text('description');
            $table->integer('published_at');
            $table->text('content');
            $table->text('url_image');
            $table->string('category');
            $table->boolean('is_headline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
