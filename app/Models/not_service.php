<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class not_service extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_number',
        'agent',
        'date',
        'status',
        'remarks',
    ];


      public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(user::class, 'agent');
    }

    public static function getSalesByAgentAndMonth($agent_id, $month, $year)
    {
        return self::where('a_name', $agent_id)
            ->where('status', 'sale')
            ->whereMonth('regitr_date', $month)
            ->whereYear('regitr_date', $year)
            ->count();
    }

}
