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
        Schema::create('prescription_eye_glasses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');

            // General fields
            $table->decimal('pd', 5, 2)->nullable();   // Pupillary Distance
            $table->decimal('npd', 5, 2)->nullable();  // Near Pupillary Distance

            // Right Eye (R.E)
            $table->string('re_ds')->nullable();
            $table->string('re_cyl')->nullable();
            $table->string('re_axis')->nullable();
            $table->string('re_add')->nullable();
            $table->string('re_pht')->nullable();
            $table->string('re_mar')->nullable();

            // Left Eye (L.E)
            $table->string('le_ds')->nullable();
            $table->string('le_cyl')->nullable();
            $table->string('le_axis')->nullable();
            $table->string('le_add')->nullable();
            $table->string('le_pht')->nullable();
            $table->string('le_mar')->nullable();

            // Other specs
            $table->text('other_specifications')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_eye_glasses');
    }
};
