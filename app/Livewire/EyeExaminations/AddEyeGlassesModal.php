<?php

namespace App\Livewire\EyeExaminations;

use Livewire\Component;

class AddEyeGlassesModal extends Component
{
    public $show = false;

    public $re_ds;
    public $re_cyl;
    public $re_axis;
    public $re_add;
    public $le_ds;
    public $le_cyl;
    public $le_axis;
    public $le_add;
    public $pd;
    public $npd;
    public $specs;

    protected $listeners = ['showAddEyeGlassesModal' => 'open'];

    protected $rules = [
        'pd' => 'nullable|numeric',
        'npd' => 'nullable|numeric',
        're_ds' => 'nullable|string',
        'le_ds' => 'nullable|string',
    ];

    public function open()
    {
        $this->reset([
            're_ds', 're_cyl', 're_axis', 're_add',
            'le_ds', 'le_cyl', 'le_axis', 'le_add',
            'pd', 'npd', 'specs'
        ]);
        $this->show = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'type' => 'eye_glasses',
            're' => [
                'ds' => $this->re_ds,
                'cyl' => $this->re_cyl,
                'axis' => $this->re_axis,
                'add' => $this->re_add,
            ],
            'le' => [
                'ds' => $this->le_ds,
                'cyl' => $this->le_cyl,
                'axis' => $this->le_axis,
                'add' => $this->le_add,
            ],
            'pd' => $this->pd,
            'npd' => $this->npd,
            'specs' => $this->specs,
        ];

        $this->dispatch('prescriptionsAdded', $data);
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.eye-examinations.add-eye-glasses-modal');
    }
}

