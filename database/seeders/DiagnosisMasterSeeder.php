<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DiagnosisMaster;
use Maatwebsite\Excel\Facades\Excel;

class DiagnosisMasterSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = storage_path('app/data/DiagnosisMaster.xlsx');

        $rows = Excel::toArray([], $filePath)[0];
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            DiagnosisMaster::create([
                'id' => $row[0], // DiagnosisNo
                'code' => $row[1] ?? null,
                'name' => $row[2] ?? null,
                'tblind_irreversility' => $row[3] ?? null,
                'employee_id' => $row[4] ?? null,
                'patient_id' => $row[5] ?? null,
            ]);
        }
    }
}

