<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class renewal extends Model
{
   protected  $fillable = [
        'customer_id',
        'renewal_date',
        'price',
        'remarks',
    ];
}
