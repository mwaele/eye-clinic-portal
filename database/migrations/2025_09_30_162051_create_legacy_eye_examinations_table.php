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
        Schema::create('legacy_eye_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();

            // Primary and secondary diagnosis references
            $table->foreignId('diagnosis_type1_id')->nullable()->constrained('diagnosis_master')->nullOnDelete();
            $table->foreignId('diagnosis_type2_id')->nullable()->constrained('diagnosis_master')->nullOnDelete();
            $table->foreignId('diagnosis_type3_id')->nullable()->constrained('diagnosis_master')->nullOnDelete();

            // Visual acuity & related fields
            $table->string('visual_acuity_r')->nullable();
            $table->string('visual_acuity_l')->nullable();
            $table->string('iop_r')->nullable();
            $table->string('iop_l')->nullable();
            $table->string('fundoscopy_r')->nullable();
            $table->string('fundoscopy_l')->nullable();
            $table->string('refraction_r')->nullable();
            $table->string('refraction_l')->nullable();

            // Diagnosis RL indicators
            $table->string('diagnosis_type1_rl')->nullable();
            $table->string('diagnosis_type2_rl')->nullable();
            $table->string('diagnosis_type3_rl')->nullable();

            // Misc fields
            $table->string('surgery_age_wise')->nullable();
            $table->string('medicine_given')->nullable();
            $table->string('surgical_type')->nullable();
            $table->string('referral')->nullable();
            $table->string('admission')->nullable();
            $table->string('discharges')->nullable();

            // Dates
            $table->dateTime('date_of_examination')->nullable();
            $table->date('date_of_next_visit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eye_examinations');
    }
};
