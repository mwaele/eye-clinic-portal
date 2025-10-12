<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EyeExamination extends Model
{
    protected $fillable = [
        'visit_id',
        'visual_acuity_r',
        'visual_acuity_l',
        'iop_r',
        'iop_l',
        'fundoscopy_r',
        'fundoscopy_l',
        'refraction_r',
        'refraction_l',
        'date_of_examination',
        'date_of_next_visit',
        'notes',
    ];

    // 🔗 Relationships
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function diagnoses()
    {
        return $this->belongsToMany(
            DiagnosisMaster::class,
            'eye_examination_diagnosis',
            'eye_examination_id',
            'diagnosis_master_id'
        );
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}

