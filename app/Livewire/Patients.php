<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patient;
use Carbon\Carbon;

class Patients extends Component
{
    use WithPagination;

    public $showModal = false;
    public $first_name, $last_name, $phone, $age, $address;

    public $search = ''; // 🆕 For search input

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'phone'      => 'required|string|max:20',
        'age'        => 'nullable|integer',
        'address'    => 'nullable|string|max:255',
    ];

    public function updatingSearch()
    {
        // Reset pagination when search changes
        $this->resetPage();
    }

    public function render()
    {
        $patients = Patient::where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('patient_no', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            })
            ->where('visit_date', '>=', Carbon::now()->subDays(30))
            ->orderByDesc('legacy_patient_id')
            ->paginate(10);

        return view('livewire.patients', [
            'patients' => $patients,
        ]);
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->showModal = true;
    }

    public function registerPatient()
    {
        $this->validate();

        $patient = new Patient();
        $patient->name = "{$this->first_name} {$this->last_name}";
        $patient->phone = ltrim($this->phone, '0'); // Store without leading zero
        $patient->age = $this->age;
        $patient->address = $this->address;
        $patient->visit_date = now();
        $patient->patient_no = Patient::max('patient_no') + 1; // auto increment
        $patient->save();

        session()->flash('message', 'Patient registered successfully!');
        $this->showModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->phone = '';
        $this->age = '';
        $this->address = '';
    }

    public function edit($id)
    {
        // You can implement edit logic here later
    }

    public function checkIn($id)
    {
        // Logic for checking in a patient for a new visit
    }
}

// <?php

// namespace App\Livewire;

// use Livewire\Component;
// use Livewire\WithPagination;
// use App\Models\Patient;
// use Carbon\Carbon;

// class Patients extends Component
// {
//     public $showModal = false;

//     public $first_name;
//     public $last_name;
//     public $phone;
//     public $age;
//     public $address;

//     protected $rules = [
//         'first_name' => 'required|string|max:100',
//         'last_name'  => 'required|string|max:100',
//         'phone'      => 'required|string|max:20',
//         'age'        => 'required|integer|min:0|max:120',
//         'address'    => 'nullable|string|max:255',
//     ];

//     public function render()
//     {
//         $patients = Patient::where('created_at', '>=', Carbon::now()->subDays(30))
//             ->orderBy('legacy_patient_id', 'desc')
//             ->paginate(10);

//         return view('livewire.patients', [
//             'patients' => $patients,
//         ]);
//     }

//     public function openModal()
//     {
//         $this->resetForm();
//         $this->showModal = true;
//     }

//     public function registerPatient()
//     {
//         $this->validate();

//         // Auto increment patient_no from last Excel record
//         $lastPatientNo = Patient::max('patient_no') ?? 0;

//         Patient::create([
//             'patient_no' => $lastPatientNo + 1,
//             'legacy_patient_id' => null,
//             'name' => strtoupper($this->first_name . ' ' . $this->last_name),
//             'phone' => $this->phone,
//             'age' => $this->age,
//             'address' => $this->address,
//         ]);

//         $this->showModal = false;
//         session()->flash('message', 'Patient registered successfully.');
//     }

//     public function resetForm()
//     {
//         $this->first_name = '';
//         $this->last_name = '';
//         $this->phone = '';
//         $this->age = '';
//         $this->address = '';
//     }
// }




