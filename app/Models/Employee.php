<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['nik', 'name', 'email' ,'password', 'photo', 'api_token'];

    protected $hidden = [
        'password', 'api_token',
    ];


    public function todos()
    {
        return $this->hasMany(\App\Models\Todo::class,'nik','nik');
    }

    public function attendence()
    {
        return $this->hasMany(\App\Models\Attendence::class,'nik','nik');
    }
}
