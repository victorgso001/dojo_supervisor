<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

use App\Students;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';
    protected $guarded = ['id'];

    public function students()
    {
        return $this->belongsTo(Students::class);
    }
}
