<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    // 1. Tell Laravel the exact table name
    protected $table = 'Patient';

    // 2. Tell Laravel the primary key name
    protected $primaryKey = 'patient_id';

    // 3. Turn off default timestamps because they aren't in your SQL
    public $timestamps = false;

    // 4. Allow these fields to be filled by your forms
    protected $fillable = [
        'patient_firstname',
        'patient_lastname',
        'patient_dob',
        'patient_phone',
        'patient_gender',
        'patient_address'
    ];

    // 5. Define the Relationship (1 Patient has Many Appointments)
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'patient_id');
    }
}