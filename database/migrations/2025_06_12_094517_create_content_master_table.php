<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentMasterTable extends Migration
{
    public function up()
    {
        Schema::create('content_master', function (Blueprint $table) {
            $table->id();
            $table->string('movie_name', 255);
            $table->foreignId('genre_id')->constrained('genre_master')->onDelete('cascade');
            $table->string('thumbnail', 255)->nullable();
            $table->string('trailer_url', 255)->nullable();
            $table->year('release_year');
            $table->text('description')->nullable();
            $table->string('language', 100)->nullable();
            $table->integer('duration')->nullable();
            $table->string('content_rating', 50)->nullable();
            $table->string('full_video')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('content_master');
    }
}

