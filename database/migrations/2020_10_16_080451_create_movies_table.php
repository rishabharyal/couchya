<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unogs_id')->unique();
            $table->unsignedBigInteger('netflix_id')->unique();
            $table->text('image')->nullable();
            $table->text('poster')->nullable();
            $table->string('vtype')->nullable();
            $table->string('imdb_id')->nullable();
            $table->string('title')->nullable();
            $table->text('clist')->nullable();
            $table->text('synopsis')->nullable();
            $table->integer('imdb_rating')->nullable();
            $table->date('title_date')->nullable();
            $table->decimal('average_rating');
            $table->integer('release_year');
            $table->string('runtime')->nullable();
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
        Schema::dropIfExists('movies');
    }
}
