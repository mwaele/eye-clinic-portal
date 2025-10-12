<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LegacyEyeExamination;
use App\Models\Patient;
use App\Models\DiagnosisMaster;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class LegacyEyeExaminationsSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = storage_path('app/data/LegacyEyeExamination.xlsx');

        $rows = Excel::toArray([], $filePath)[0];
        $headers = array_shift($rows);

        foreach ($rows as $index => $row) {

            // Skip first 9 dummy records
            if ($index < 9) continue;

            $legacyPatientId = $row[21] ?? null;
            $patient = Patient::where('legacy_patient_id', $legacyPatientId)->first();

            if (!$patient) continue;

            LegacyEyeExamination::create([
                'id' => $row[0], // ExamNo
                'patient_id' => $patient->id,
                'diagnosis_type1_id' => $row[9] ?? null,
                'diagnosis_type1_rl' => $row[10] ?? null,
                'diagnosis_type2_id' => $row[11] ?? null,
                'diagnosis_type2_rl' => $row[12] ?? null,
                'diagnosis_type3_id' => $row[13] ?? null,
                'diagnosis_type3_rl' => $row[14] ?? null,
                'visual_acuity_r' => $row[1] ?? null,
                'visual_acuity_l' => $row[2] ?? null,
                'iop_r' => $row[3] ?? null,
                'iop_l' => $row[4] ?? null,
                'fundoscopy_r' => $row[5] ?? null,
                'fundoscopy_l' => $row[6] ?? null,
                'refraction_r' => $row[7] ?? null,
                'refraction_l' => $row[8] ?? null,
                'surgery_age_wise' => $row[15] ?? null,
                'medicine_given' => $row[16] ?? null,
                'surgical_type' => $row[17] ?? null,
                'referral' => $row[18] ?? null,
                'admission' => $row[19] ?? null,
                'discharges' => $row[20] ?? null,
                'date_of_examination' => !empty($row[22]) ? Carbon::parse($row[22]) : null,
                'date_of_next_visit' => !empty($row[23]) ? Carbon::parse($row[23]) : null,
            ]);
        }
    }
}
