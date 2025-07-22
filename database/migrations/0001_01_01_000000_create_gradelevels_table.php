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
        Schema::create('gradelevels', function (Blueprint $table) {
            $table->id();
            $table->enum('grade_level',['الصف الاول الثانوي','الصف الثاني الثانوي','الصف الثالث الثانوي']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gradelevels');
    }
};
