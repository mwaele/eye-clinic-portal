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
        'description',
    ];

    public function eyeExaminations()
    {
        return $this->belongsToMany(
            EyeExamination::class,
            'eye_examination_diagnosis',
            'diagnosis_master_id',
            'eye_examination_id'
        );
    }
}
