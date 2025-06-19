<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastMasterTable extends Migration
{
    public function up()
    {
        Schema::create('cast_master', function (Blueprint $table) {
            $table->id();
            $table->string('cast_name', 100)->unique();
            $table->string('image', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('role_name', 100);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cast_master');
    }
}
