<?php

namespace App\Livewire\EyeExaminations;

use Livewire\Component;

class AddEyeDropsModal extends Component
{
    public $show = false;
    public $search = '';
    public $availableDrops = [];
    public $selectedDrops = [];

    protected $listeners = ['showAddEyeDropsModal' => 'open'];

    public function open()
    {
        $this->reset(['search', 'selectedDrops']);
        $this->show = true;
        $this->loadAvailableDrops();
    }

    public function loadAvailableDrops()
    {
        // Temporary static list — later hook to Inventory model
        $this->availableDrops = [
            ['id' => 1, 'name' => 'MAXITROL'],
            ['id' => 2, 'name' => 'COH DEXA'],
            ['id' => 3, 'name' => 'DEXAPO3'],
        ];
    }

    public function selectDrop($id)
    {
        $drop = collect($this->availableDrops)->firstWhere('id', $id);

        if ($drop && !collect($this->selectedDrops)->pluck('id')->contains($id)) {
            $this->selectedDrops[] = $drop;
        }
    }

    public function save()
    {
        $this->dispatch('prescriptionsAdded', [
            'type' => 'eye_drops',
            'items' => $this->selectedDrops
        ]);

        $this->show = false;
    }

    public function render()
    {
        return view('livewire.eye-examinations.add-eye-drops-modal');
    }
}
