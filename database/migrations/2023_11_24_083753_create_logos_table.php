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
        Schema::create('logos', function (Blueprint $table) {
            $table->id();
            $table->integer('topic_id');
            $table->integer('family_id');
            $table->integer('font_id');
            $table->integer('palette_id');
            $table->integer('icon_id');
            $table->enum('type', ['text_only', 'text_icon_top', 'text_icon_left']);
            $table->enum('status', ['pending', 'completed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logos');
    }
};
