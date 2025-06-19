<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenreMasterTable extends Migration
{
    public function up()
    {
        Schema::create('genre_master', function (Blueprint $table) {
            $table->id();
            $table->string('genre_name', 100)->unique();
            $table->string('image', 255)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genre_master');
    }
}
