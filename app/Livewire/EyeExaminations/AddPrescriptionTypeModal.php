<?php

namespace App\Livewire\EyeExaminations;

use Livewire\Component;

class AddPrescriptionTypeModal extends Component
{
    public $show = false;
    public $types = ['Eye Drops', 'Eye Glasses'];
    public $selectedType = null;

    protected $listeners = ['showAddPrescriptionTypeModal' => 'open'];

    public function open()
    {
        $this->reset('selectedType');
        $this->show = true;
    }

    public function selectType($type)
    {
        $this->selectedType = $type;
        $this->show = false;

        if ($type === 'Eye Drops') {
            $this->dispatch('showAddEyeDropsModal');
        } elseif ($type === 'Eye Glasses') {
            $this->dispatch('showAddEyeGlassesModal');
        }
    }

    public function render()
    {
        return view('livewire.eye-examinations.add-prescription-type-modal');
    }
}

