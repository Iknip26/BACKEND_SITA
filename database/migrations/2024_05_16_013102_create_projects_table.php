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
            $table->foreignId("lecturer_id");
            $table->string("title");
            $table->string("agency");
            $table->string("description");
            $table->string("tools");
            $table->string("instance");
            $table->enum("status",["bimbingan","revisi","proses"]);
            $table->enum("isApproved",["Approved","Not Approved", "Not yet Approved"]);
            $table->timestamps();
            $table->foreign("lecturer_id")->references('id')->on('lecturers');
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