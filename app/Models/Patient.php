<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'patient_no',
        'legacy_patient_id',
        'name',
        'address',
        'phone',
        'dob',
        'age',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'dob' => 'date',
    ];


    public function eyeExaminations()
    {
        return $this->hasMany(EyeExamination::class);
    }

    public function diagnosisMasters()
    {
        return $this->hasMany(DiagnosisMaster::class);
    }

}
