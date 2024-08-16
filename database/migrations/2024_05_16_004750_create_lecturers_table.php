<?php

use App\Models\User;
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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id");
            $table->string("front_title")->nullable();
            $table->string("back_title")->nullable();
            $table->string("NID");
            $table->string("photo_profile")->nullable();
            $table->integer("max_quota");
            $table->integer("remaining_quota");
            $table->string("phone_number");
            $table->boolean("isKaprodi");
            $table->timestamps();
            $table->foreign("user_id")->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
