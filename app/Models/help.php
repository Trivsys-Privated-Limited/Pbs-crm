<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class help extends Model
{
    protected $fillable = [
        'c_name',
        'c_number',
        'c_email',
        'make_address',
        'help_reason',
        'user_id',
        'user_name',
        'status',
        'remarks'
    ];

    public function user(){
        $this->belongsTo(user::class);
    }
}
