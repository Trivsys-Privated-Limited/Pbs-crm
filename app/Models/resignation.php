<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class resignation extends Model
{
    protected $table = 'resignations';

    protected $fillable = [
        'employee_id',
        'resignation_date',
        'reason',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
