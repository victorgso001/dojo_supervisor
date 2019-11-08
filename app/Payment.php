<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

use App\Students;

class Payment extends Model
{
    protected $table = 'payments';

    public function students()
    {
        return $this->belongsTo(Students::class);
    }
}
