<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionEyeDrop extends Model
{
    protected $fillable = [
        'prescription_id',
        'name',
        'inventory_item_id',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
