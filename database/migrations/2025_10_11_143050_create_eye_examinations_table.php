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
        Schema::create('eye_examinations', function (Blueprint $table) {
            $table->id();

            // 🔗 Linked to visit
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');

            // 👁️ Eye examination details
            $table->unsignedBigInteger('legacy_exam_id')->nullable()->unique();
            $table->string('visual_acuity_r')->nullable();
            $table->string('visual_acuity_l')->nullable();
            $table->string('iop_r')->nullable(); // intraocular pressure
            $table->string('iop_l')->nullable();
            $table->text('fundoscopy_r')->nullable();
            $table->text('fundoscopy_l')->nullable();
            $table->text('refraction_r')->nullable();
            $table->text('refraction_l')->nullable();

            // 📅 Dates
            $table->date('date_of_examination')->nullable();
            $table->date('date_of_next_visit')->nullable();

            // 🩺 Notes or general remarks
            $table->text('notes')->nullable();

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
