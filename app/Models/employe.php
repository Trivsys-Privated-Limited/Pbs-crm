<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employe extends Model
{
    protected $fillable = [
        'employe_id',
        'resume',
        'offer_letter',
        'cnic',
        'employe_type',
        'profile_img',
        'salary',
        'target',
        'late',
        'leave',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employe_id');
    }
}
