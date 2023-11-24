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
        Schema::create('topic', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('family1_id');
            $table->integer('family2_id');
            $table->string('company_name');
            $table->string('desc');
            $table->string('suggestions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic');
    }
};
