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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('channer_id');
            $table->string('news_id')->unique();
            $table->string('avatar')->nullable();
            $table->string('news_title');
            $table->string('thumbnail_photo')->nullable();
            $table->string('context');
            $table->string('source')->nullable();
            $table->string('source_link')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
