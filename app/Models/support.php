<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class support extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'number', 
        'agent_name', 
        'expiry_date', 
        'show_status', 
        'status',
         'remarks', // <--- YEH NAYI LINE ADD KAREIN 
        'assigned_by_name', 
        'assigned_by_role', 
        'assigned_date',
        'assigned_to'
    ];
}
