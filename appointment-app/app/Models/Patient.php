<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patient';
    protected $primaryKey = 'patient_id';
    public $timestamps = false;

    protected $fillable = [
        'patient_firstname',
        'patient_lastname',
        'patient_email',
        'patient_phone',
        'patient_dob',
    ];
}