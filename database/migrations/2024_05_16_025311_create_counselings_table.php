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
            $table->foreignId("project_id");
            $table->date("date")->default(today());
            $table->string("subject");
            $table->text("description")->nullable();
            $table->string("file")->nullable();
            $table->enum("status",['revision','ok'])->nullable();
            $table->integer("progress");
            $table->string("lecturer_note")->nullable();
            $table->timestamps();
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
