<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class client_number extends Model
{
   use HasFactory;
    // region add kar dain
    protected $fillable = ['number', 'date', 'region'];

}
