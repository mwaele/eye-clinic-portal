<?php

namespace App\Livewire;

use Livewire\Component;

class EyeExaminations extends Component
{
    public function render()
    {
        $activeVisits = Visit::with('patient')
            ->where('status', 'active')
            ->latest()
            ->get();

        $closedVisits = Visit::with('patient', 'examination')
            ->where('status', 'closed')
            ->latest()
            ->get();

        return view('livewire.eye-examinations', compact('activeVisits', 'closedVisits'));
    }
}
