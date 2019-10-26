<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    public function dojo_class() {
        return $this->hasOne('App\Class');
    }

    public function payments() {
        return $this->hasMany('App\Payment');
    }

    public function teacher() {
        return $this->hasMany('App\Teacher');
    }

    public function graduation() {
        return $this->hasOne('App\Graduation');
    }
}
