<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisMaster extends Model
{
    protected $table = 'diagnosis_master';
    public $timestamps = true;
    protected $fillable = [
        'code',
        'name',
        'tblind_irreversility',
        'employee_id',
        'patient_id',
    ];

    public function eyeExaminations()
    {
        return $this->hasMany(EyeExamination::class, 'diagnosis_type1_id')
                    ->orWhere('diagnosis_type2_id', $this->id)
                    ->orWhere('diagnosis_type3_id', $this->id);
    }

    public function patients()
    {
        return $this->belongsTo(Patient::class);
    }
}
