<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentCastTable extends Migration
{
    public function up()
    {
        Schema::create('content_cast', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('content_master')->onDelete('cascade');
            $table->foreignId('cast_id')->constrained('cast_master')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('content_cast');
    }
}
