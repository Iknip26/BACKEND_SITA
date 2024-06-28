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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id");
            $table->string("NIM");
            $table->integer("semester");
            $table->float("IPK");
            $table->integer("SKS");
            $table->string("phone_number")->nullable();
            $table->boolean('sidang')->default(false);
            $table->boolean('judul')->default(false);
            $table->boolean('yudisium')->default(false);
            $table->timestamps();
            $table->foreign("user_id")->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
