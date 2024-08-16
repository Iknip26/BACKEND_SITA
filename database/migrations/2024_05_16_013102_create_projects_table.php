<?php

use App\Models\Lecturer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Reference\Reference;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId("lecturer1_id")->nullable();
            $table->foreignId("lecturer2_id")->nullable();
            $table->foreignId("student_id")->nullable();
            $table->string("title");
            $table->string("agency");
            $table->text("description")->nullable();
            $table->string("tools");
            $table->string("instance");
            $table->enum("status",["counseling","not approved","process","not taken yet"])->nullable();
            $table->enum("Approval_lecturer_1",["Approved","Not Approved", "Not yet Approved"])->nullable();
            $table->enum("Approval_lecturer_2",["Approved","Not Approved", "Not yet Approved"])->nullable();
            $table->enum("Approval_kaprodi",["Approved","Not Approved", "Not yet Approved"])->nullable();
            $table->string("year");
            $table->enum('uploadedBy',['Dosen', 'Mahasiswa']);
            $table->timestamps();

            $table->foreign("lecturer1_id")->references('id')->on('lecturers');
            $table->foreign("lecturer2_id")->references('id')->on('lecturers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
