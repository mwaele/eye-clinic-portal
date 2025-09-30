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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_no')->nullable();
            $table->integer('legacy_patient_id')->unique()->nullable(); // maps to PatientID in Excel
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('sex', 10)->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->dateTime('visit_date')->nullable();
            $table->integer('visit_no')->nullable();
            $table->decimal('consult_fee', 10, 2)->nullable();
            $table->integer('employee_id')->nullable();
            $table->date('next_visit_date')->nullable();
            $table->decimal('lens_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
