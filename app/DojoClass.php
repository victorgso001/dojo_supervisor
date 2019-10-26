<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class DojoClass extends Model
{
    protected $table = 'classes';

    protected $casts = [
        'begin_hour' => 'date:hh:mm',
        'end_hour' => 'date:hh:mm',
    ];
}
