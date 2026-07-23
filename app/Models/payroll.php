<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'month',
        'basic_salary',
        'absent_days',
        'late_count',
        'absent_deduction',
        'late_deduction',
        'advance_deduction',
        'manual_deduction',
        'commission',
        'net_salary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
