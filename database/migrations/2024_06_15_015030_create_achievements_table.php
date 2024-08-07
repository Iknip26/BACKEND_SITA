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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id");
            $table->string('achievement_name');
            $table->string('achievement_type');
            $table->string('achievement_level');
            $table->string('achievement_year');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->foreign("student_id")->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
