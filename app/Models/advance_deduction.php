<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class advance_deduction extends Model
{
    protected $table = 'advance_deductions';

    protected $fillable = [
        'advance_id',
        'employee_id',
        'month',
        'deducted_amount',
    ];

       public function advance()
    {
        return $this->belongsTo(advance::class, 'advance_id');
    }
       public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
