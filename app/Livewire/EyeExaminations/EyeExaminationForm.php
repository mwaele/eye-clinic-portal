<?php

namespace App\Livewire\EyeExaminations;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Visit;
use App\Models\EyeExamination;
use App\Models\Prescription;
use App\Models\PrescriptionEyeDrop;
use App\Models\PrescriptionEyeGlass;
use App\Models\DiagnosisMaster;
use Carbon\Carbon;

class EyeExaminationForm extends Component
{
    // public props
    public ?int $visit_id = null; // provided when opening form

    public $tab = 'diagnosis';

    // form fields
    public $visual_acuity_r;
    public $visual_acuity_l;
    public $notes;

    public $selectedDiagnoses = [];

    // prescriptions live structure: array of arrays, each: ['type' => 'eye_drops'|'eye_glasses', 'data' => array]
    public $prescriptions = [];

    protected $listeners = [
        'addPrescription' => 'handleAddPrescription',
    ];

    protected $rules = [
        'visit_id' => 'required|exists:visits,id',
        'visual_acuity_r' => 'nullable|string|max:50',
        'visual_acuity_l' => 'nullable|string|max:50',
        'notes' => 'nullable|string',
        'selectedDiagnoses' => 'nullable|array',
    ];

    public function mount($visitId = null)
    {
        $this->visit_id = $visitId;

        // if visit provided, you may want to pre-load an exam draft etc.
    }

    public function render()
    {
        $diagnoses = DiagnosisMaster::orderBy('name')->get();
        $visit = $this->visit_id ? Visit::with('patient')->find($this->visit_id) : null;

        return view('livewire.eye-examinations.eye-examination-form', [
            'diagnosesMaster' => $diagnoses,
            'visitModel' => $visit,
        ])->layout('layouts.app');
    }

    // Called when child modal emits an addPrescription event
    public function handleAddPrescription($payload)
    {
        // payload should be an array: ['type' => 'eye_drops'|'eye_glasses', 'data' => [...]]
        $this->prescriptions[] = $payload;
    }

    public function removePrescription($index)
    {
        if (isset($this->prescriptions[$index])) {
            array_splice($this->prescriptions, $index, 1);
        }
    }

    public function switchTab($tab)
    {
        $this->tab = $tab;
    }

    public function save()
    {
        $this->validate();

        // create eye examination under visit
        $visit = Visit::findOrFail($this->visit_id);

        $exam = EyeExamination::create([
            'visit_id' => $visit->id,
            'visual_acuity_r' => $this->visual_acuity_r,
            'visual_acuity_l' => $this->visual_acuity_l,
            'notes' => $this->notes,
            'date_of_examination' => now(),
        ]);

        // diagnoses pivot
        if (!empty($this->selectedDiagnoses)) {
            $exam->diagnoses()->sync($this->selectedDiagnoses);
        }

        // prescriptions
        foreach ($this->prescriptions as $p) {
            $type = $p['type'] ?? null;
            $data = $p['data'] ?? [];

            if ($type === 'eye_drops') {
                $pres = $exam->prescriptions()->create(['type' => 'eye_drops']);

                // data expected: ['drops' => [['name'=>'MAXITROL'], ...]]
                foreach ($data['drops'] ?? [] as $drop) {
                    PrescriptionEyeDrop::create([
                        'prescription_id' => $pres->id,
                        'name' => $drop['name'] ?? null,
                        'inventory_id' => $drop['inventory_id'] ?? null,
                    ]);
                }

            } elseif ($type === 'eye_glasses') {
                $pres = $exam->prescriptions()->create(['type' => 'eye_glasses']);

                // data expected contains parsed refraction fields and pd/npd
                PrescriptionEyeGlass::create(array_merge([
                    'prescription_id' => $pres->id,
                ], $data));
            }
        }

        session()->flash('success', 'Eye examination saved.');

        // reset state or redirect
        return redirect()->route('eye-examinations.index');
    }

    // Utility parsing method for refractions (can be used by glasses modal too)
    public function parseRefraction(string $value): array
    {
        $value = strtoupper(trim($value));
        $value = preg_replace('/\s+/', '', $value);
        $value = str_replace(['PLAN0','PLANO'], '0.00', $value);

        $result = ['ds' => null, 'cyl' => null, 'axis' => null, 'add' => null];

        if (preg_match('/(-?\d+\.?\d*)\/(-?\d+\.?\d*)\*(\d+)/', $value, $m)) {
            $result['ds'] = $m[1];
            $result['cyl'] = $m[2];
            $result['axis'] = $m[3];
            return $result;
        }

        if (preg_match('/(-?\d+\.?\d*)\s*ADD\s*\+?(\d+\.?\d*)/i', $value, $m)) {
            $result['ds'] = $m[1];
            $result['add'] = '+' . $m[2];
            return $result;
        }

        if (preg_match('/^(-?\d+\.?\d*)$/', $value, $m)) {
            $result['ds'] = $m[1];
            return $result;
        }

        if (stripos($value, 'PLANO') !== false) {
            $result['ds'] = '0.00';
            // attempt to capture add
            if (preg_match('/ADD\+?(\d+\.?\d*)/', $value, $m2)) {
                $result['add'] = '+' . $m2[1];
            }
            return $result;
        }

        return $result;
    }
}