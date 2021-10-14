<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->boolean('state')->default(1);
            $table->string('alias');
            $table->string('title_ru');
            $table->string('title_ge');
            $table->string('title_en');
            $table->mediumText('desc_ru');
            $table->mediumText('desc_ge');
            $table->mediumText('desc_en');
            $table->string('image')->nullable();
            $table->unsignedInteger('rate')->default(0);;
            $table->unsignedInteger('hits')->default(0);;
            $table->timestamps();
        });
        DB::statement('ALTER TABLE artists ADD FULLTEXT search(title_ru, title_ka, title_en)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('artists', function($table) {
            $table->dropIndex('search');
        });
        Schema::dropIfExists('artists');
    }
}
