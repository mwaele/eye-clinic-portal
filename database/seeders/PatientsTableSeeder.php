<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class PatientsTableSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/PatDetails.xlsx'); // place file here

        $rows = Excel::toArray([], $filePath)[0];

        // Remove header row
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            Patient::create([
                'patient_no' => $row[0] ?? null,
                'legacy_patient_id' => $row[1] ?? null,
                'name' => $row[2] ?? null,
                'address' => $row[3] ?? null,
                'phone' => $row[4] ?? null,
                'dob' => !empty($row[5]) ? Carbon::parse($row[5]) : null,
                'age' => $row[6] ?? null,
                'sex' => $row[7] ?? null,
                'blood_group' => $row[8] ?? null,
                'visit_date' => !empty($row[9]) ? (
                    is_numeric($row[9])
                        ? Carbon::instance(ExcelDate::excelToDateTimeObject($row[9]))
                        : Carbon::createFromFormat('d/m/Y', $row[9])
                ) : null,
                'visit_no' => $row[10] ?? null,
                'consult_fee' => $row[11] ?? null,
                'employee_id' => $row[12] ?? null,
                'next_visit_date' => !empty($row[16]) ? Carbon::parse($row[16]) : null,
                'lens_price' => $row[17] ?? null,
            ]);
        }
    }
}

