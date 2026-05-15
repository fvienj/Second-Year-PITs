<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'Patient';
    protected $primaryKey = 'patient_id';
    public $timestamps = false; // Your schema doesn't have created_at/updated_at columns

    protected $fillable = [
        'patient_firstname',
        'patient_lastname',
        'patient_email',
        'patient_dob',
        'patient_phone',
        'patient_gender',
        'patient_address'
    ];
}