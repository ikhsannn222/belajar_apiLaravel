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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->unique();
            $table->text('deskripsi');
            $table->string('foto');
            $table->string('url_video');
            $table->unsignedbigInteger('id_kategori');
            $table->foreign('id_kategori')->references('id')->on('kategoris')
            ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('genre_films', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('id_genre');
            $table->unsignedbigInteger('id_film');
            $table->foreign('id_genre')->references('id')->on('genres')
            ->onDelete('cascade');
            $table->foreign('id_film')->references('id')->on('films')
            ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('actor_films', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('id_actor');
            $table->unsignedbigInteger('id_film');
            $table->foreign('id_actor')->references('id')->on('actors')
            ->onDelete('cascade');
            $table->foreign('id_film')->references('id')->on('films')
            ->onDelete('cascade');
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
        Schema::dropIfExists('films');
        Schema::dropIfExists('genre_films');
        Schema::dropIfExists('actor_films');
    }
};
