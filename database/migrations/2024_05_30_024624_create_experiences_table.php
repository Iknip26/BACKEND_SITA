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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId("student_id");
            $table->string('position');
            $table->string('company_name');
            $table->string('field');
            $table->string('duration');
            $table->string('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->foreign("student_id")->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};