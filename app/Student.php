<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Student extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $table = 'students';

    protected $guarded = ['id'];

    public function dojoClass()
    {
        return $this->hasOne('App\Class');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function teacher()
    {
        return $this->hasMany('App\Teacher');
    }

    public function graduation()
    {
        return $this->hasOne('App\Graduation');
    }
}
