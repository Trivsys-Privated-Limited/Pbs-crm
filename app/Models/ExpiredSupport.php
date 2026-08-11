<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpiredSupport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'number', 
        'agent_name', 
        'old_expiry_date', 
        'show_status',
        'assigned_to'
    ];
}