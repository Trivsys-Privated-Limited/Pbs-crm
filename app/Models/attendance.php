<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class attendance extends Model
{
    protected $table    = 'attendances'; 
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'total_hours',
        'status',
        'employee_name',
    ];

    public $timestamps = false;
}
