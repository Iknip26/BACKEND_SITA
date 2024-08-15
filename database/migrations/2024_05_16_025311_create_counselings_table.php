<?php

use App\Models\Lecturer;
use App\Models\Project;
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
        Schema::create('counselings', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId("student_id");
            $table->foreignId("lecturer1_id");
            $table->foreignId("lecturer2_id");
            $table->foreignId("project_id");
            $table->date("date")->default(today());
            $table->string("subject");
            $table->string("lecturer_note")->nullable();
            $table->string("file")->nullable();
            $table->string("status");
            $table->string("progress");
            $table->timestamps();
            $table->foreign("student_id")->references('id')->on('students');
            $table->foreign("lecturer1_id")->references('id')->on('lecturers');
            $table->foreign("lecturer2_id")->references('id')->on('lecturers');
            $table->foreign("project_id")->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counselings');
    }
};
