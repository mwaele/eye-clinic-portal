<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\EyeExamination;
use App\Models\Visit;
use Carbon\Carbon;

class MigrateLegacyEyeExaminations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:eye-examinations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate legacy eye examination data to new normalized structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching legacy eye examination records...');
        $legacyRecords = DB::table('legacy_eye_examinations')->orderBy('id')->get();
        $bar = $this->output->createProgressBar($legacyRecords->count());
        $bar->start();

        foreach ($legacyRecords as $record) {
            // 🧩 Skip if this record was already migrated (same patient + date_of_examination)
            $alreadyExists = DB::table('eye_examinations')
                ->join('visits', 'eye_examinations.visit_id', '=', 'visits.id')
                ->where('visits.patient_id', $record->patient_id)
                ->whereDate('eye_examinations.date_of_examination', Carbon::parse($record->date_of_examination)->toDateString())
                ->exists();

            if ($alreadyExists) {
                $bar->advance();
                continue;
            }

            DB::transaction(function () use ($record) {
                // 1️⃣ Create Visit
                $visit = Visit::create([
                    'patient_id' => $record->patient_id,
                    'status' => 'closed', // since it's historical data
                    'visit_date' => $record->date_of_examination
                        ? Carbon::parse($record->date_of_examination)
                        : now(),
                    'created_at' => $record->created_at,
                    'updated_at' => $record->updated_at,
                ]);

                // 2️⃣ Create Eye Examination linked to visit
                $exam = EyeExamination::create([
                    'visit_id' => $visit->id,
                    'visual_acuity_r' => $record->visual_acuity_r,
                    'visual_acuity_l' => $record->visual_acuity_l,
                    'iop_r' => $record->iop_r,
                    'iop_l' => $record->iop_l,
                    'fundoscopy_r' => $record->fundoscopy_r,
                    'fundoscopy_l' => $record->fundoscopy_l,
                    'refraction_r' => $record->refraction_r,
                    'refraction_l' => $record->refraction_l,
                    'date_of_examination' => $record->date_of_examination
                        ? Carbon::parse($record->date_of_examination)
                        : null,
                    'date_of_next_visit' => $record->date_of_next_visit
                        ? Carbon::parse($record->date_of_next_visit)
                        : null,
                    'created_at' => $record->created_at,
                    'updated_at' => $record->updated_at,
                ]);

                // 3️⃣ Migrate Diagnoses (pivot)
                $diagnosisIds = collect([
                    $record->diagnosis_type1_id,
                    $record->diagnosis_type2_id,
                    $record->diagnosis_type3_id,
                ])->filter();

                if ($diagnosisIds->isNotEmpty()) {
                    $exam->diagnoses()->attach($diagnosisIds->toArray());
                }

                // 4️⃣ Migrate Eye Drops prescriptions (if any)
                if (!empty($record->medicine_given)) {
                    $drops = preg_split('/[,;]+/', $record->medicine_given);

                    $prescription = $exam->prescriptions()->create([
                        'type' => 'eye_drops',
                        'created_at' => $record->created_at,
                        'updated_at' => $record->updated_at,
                    ]);

                    foreach ($drops as $drop) {
                        $name = trim($drop);
                        if ($name !== '') {
                            DB::table('prescription_eye_drops')->insert([
                                'prescription_id' => $prescription->id,
                                'inventory_id' => null,
                                'created_at' => $record->created_at,
                                'updated_at' => $record->updated_at,
                            ]);
                        }
                    }
                }

                // 4️⃣ Migrate prescriptions (eye drops + eye glasses)
                if ($record->medicine_given) {
                    $items = explode(',', $record->medicine_given);

                    foreach ($items as $item) {
                        $name = strtoupper(trim($item));

                        if ($name === '') continue;

                        // ✅ Detect Eye Glasses keywords
                        if (str_contains($name, 'SPECTACLE') || str_contains($name, 'GLASS')) {
                            $prescription = $exam->prescriptions()->create(['type' => 'eye_glasses']);

                            DB::table('prescription_eye_glasses')->insert([
                                'prescription_id' => $prescription->id,
                                're_ds' => $record->refraction_r ?? null,
                                'le_ds' => $record->refraction_l ?? null,
                                'other_specifications' => 'Migrated from legacy record',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }

                        // ✅ Detect Eye Drops (default)
                        else {
                            $prescription = $exam->prescriptions()->create(['type' => 'eye_drops']);

                            DB::table('prescription_eye_drops')->insert([
                                'prescription_id' => $prescription->id,
                                'inventory_id' => null, // map later if possible
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            });

            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✅ Migration complete! All legacy records successfully normalized.");
    }
}


