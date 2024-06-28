<?php

use App\Models\Student;
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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id");
            $table->string('skill');
            $table->string('achievement name');
            $table->string('achievement type');
            $table->string('achievement level');
            $table->string('achievement year');
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
        Schema::dropIfExists('skills');
    }
};
