<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrofeoMatchup extends Model
{
    protected $fillable = [
        'trofeo_id',
        'user_id',
    ];

    public function trofeo()
    {
        return $this->belongsTo(Trofeo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
