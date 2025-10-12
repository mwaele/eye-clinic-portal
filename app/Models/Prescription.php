<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'eye_examination_id',
        'type', // 'eye_drops' or 'eye_glasses'
    ];

    public function eyeExamination()
    {
        return $this->belongsTo(EyeExamination::class);
    }

    public function eyeDrops()
    {
        return $this->hasMany(PrescriptionEyeDrop::class);
    }

    public function eyeGlasses()
    {
        return $this->hasMany(PrescriptionEyeGlass::class);
    }
}
