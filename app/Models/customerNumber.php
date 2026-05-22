<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class customerNumber extends Model
{

    protected $fillable = [
        'customer_name',
        'customer_number',
        'agent',
        'date',
        'status',
        'remarks'
    ];

    public function user(){
        return $this->belongsTo(user::class,'agent');
     }

    
}
