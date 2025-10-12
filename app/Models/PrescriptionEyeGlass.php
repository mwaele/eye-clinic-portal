<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionEyeGlass extends Model
{
    protected $fillable = [
        'prescription_id',
        'eye', // R.E or L.E
        'diopter_sphere',
        'cylinder',
        'axis',
        'addition',
        'pht',
        'mar',
        'other_specs',
        'pd',
        'npd',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
