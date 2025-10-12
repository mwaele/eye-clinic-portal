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
        Schema::create('eye_examination_diagnosis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eye_examination_id')->constrained()->onDelete('cascade');
            $table->foreignId('diagnosis_master_id')->constrained('diagnosis_master')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eye_examination_diagnosis');
    }
};
