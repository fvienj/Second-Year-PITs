<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'Appointment';
    protected $primaryKey = 'app_id';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'dentist_id',
        'service_id',
        'app_date',
        'app_timestart',
        'app_status'
    ];

    public function patient() {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function dentist() {
        return $this->belongsTo(Dentist::class, 'dentist_id');
    }
}