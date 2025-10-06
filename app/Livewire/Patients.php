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
        $this->resetPage();
    }

    public function render()
    {
        $query = Patient::query()
            ->where('visit_date', '>=', now()->subDays(30)); // ✅ filter first

        if ($this->search) {
            $search = trim($this->search);

            $query->where(function ($sub) use ($search) {
                $sub->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhere('patient_no', 'LIKE', "%{$search}%")
                    ->orWhereRaw('CONCAT("0", phone) LIKE ?', ["%{$search}%"])
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $patients = $query
            ->orderByDesc('patient_no')
            ->paginate(10);

        return view('livewire.patients', ['patients' => $patients]);
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
        $patient = Patient::findOrFail($id);

        // Check if there's already an active visit
        $activeVisit = $patient->visits()->where('status', 'active')->first();

        if (!$activeVisit) {
            $patient->visits()->create([
                'status' => 'active',
                'checked_in_at' => now(),
            ]);
            session()->flash('message', "{$patient->name} has been checked in.");
        } else {
            session()->flash('message', "{$patient->name} already has an active visit.");
        }
    }

}
