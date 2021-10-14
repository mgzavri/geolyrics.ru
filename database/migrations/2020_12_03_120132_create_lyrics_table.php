<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lyrics', function (Blueprint $table) {
            $table->id();
            $table->boolean('state')->default(1);
            $table->string('alias');
            $table->string('title_ru');
            $table->string('title_ka')->nullable();
            $table->string('title_en')->nullable();
            $table->mediumText('text_ka')->nullable();
            $table->mediumText('text_ru')->nullable();
            $table->mediumText('text_en')->nullable();
            $table->mediumText('trans_ru')->nullable();
            $table->mediumText('trans_en')->nullable();
            $table->mediumText('chords')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('rate')->default(0);
            $table->unsignedInteger('hits')->default(0);
            $table->timestamps();

        });

        DB::statement('ALTER TABLE lyrics ADD FULLTEXT search(title_ru, title_ka, title_en, text_ka, text_ru, text_en, trans_ru, trans_en)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lyrics');
    }
}
