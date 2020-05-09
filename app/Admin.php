<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use SoftDeletes;
    protected $table = 'admins';

    protected $guarded = ['id'];
}
