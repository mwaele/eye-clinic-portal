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
        Schema::create('diagnosis_master', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable(); // DiagnosisCode
            $table->string('name')->nullable(); // ClinicalDiagnosisType
            $table->string('tblind_irreversility')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('patient_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosis_master');
    }
};
