<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Students;

class Graduation extends Model
{
    protected $table = 'graduations';

    public function students() {
        return $this->belongsToMany(Students::class);
    }
}
