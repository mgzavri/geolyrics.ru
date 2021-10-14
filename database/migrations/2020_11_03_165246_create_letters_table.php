<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->boolean('state')->default(1);
            $table->string('title');
            $table->string('ka');
            $table->string('ru');
            $table->string('en');
            $table->string('word1');
            $table->string('word2');
            $table->string('word3');
            $table->string('audio1');
            $table->string('audio2');
            $table->string('audio3');
            $table->string('image1');
            $table->string('image2');
            $table->string('image3');
            $table->text('desc_ru');
            $table->text('desc_en');
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
        Schema::dropIfExists('letters');
    }
}
