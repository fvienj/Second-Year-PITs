<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'Service';
    protected $primaryKey = 'service_id';
    public $timestamps = false;

    protected $fillable = ['service_name', 'service_price'];
}