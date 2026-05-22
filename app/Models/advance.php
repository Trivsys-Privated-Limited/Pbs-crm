<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class advance extends Model
{
    protected $fillable = [
        'employee_id',
        'advance_amount',
        'monthly_amount',
        'remaining_amount',
        'start_month',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }

}
