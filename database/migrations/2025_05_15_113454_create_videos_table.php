<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('vimeo_uri')->unique(); // Example: /videos/123456789
            $table->string('vimeo_link')->nullable(); // Example: https://vimeo.com/123456789
            $table->text('embed_html')->nullable(); // Could store the full iframe or just the player URL for manual iframe construction
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
