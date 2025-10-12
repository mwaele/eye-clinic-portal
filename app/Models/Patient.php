<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'age',
        'address',
        'patient_no',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    // Accessor to prepend “0” to phone numbers
    public function getFormattedPhoneAttribute()
    {
        return '0' . ltrim($this->phone, '0');
    }
}
