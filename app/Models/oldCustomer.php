<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class oldCustomer extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_number',
        'customer_email',
        'price',
        'remarks',
        'status',
        'a_name',
        'expiry_date',
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'agent');
    }

    public static function getSalesByAgentAndMonth($agent_id, $month, $year)
    {
        return self::where('agent', $agent_id)
            ->where('status', 'sale')
            ->whereMonth('regitr_date', $month)
            ->whereYear('regitr_date', $year)
            ->count();
    }
}
