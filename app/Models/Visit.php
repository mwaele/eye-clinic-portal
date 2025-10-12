<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'patient_id',
        'status', // e.g. 'active', 'closed'
        'date_of_visit',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function eyeExaminations()
    {
        return $this->hasMany(EyeExamination::class);
    }
}
