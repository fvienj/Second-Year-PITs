<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointment';
    protected $primaryKey = 'appointment_id';

    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'dentist_id',
        'appointment_name',
        'appointment_date',
        'appointment_time',
    ];
}