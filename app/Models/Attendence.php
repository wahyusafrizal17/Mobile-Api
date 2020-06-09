<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    protected $fillable = ['nik', 'health_status', 'word_place', 'description'];
}
