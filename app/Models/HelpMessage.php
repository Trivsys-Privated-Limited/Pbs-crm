<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'help_id',
        'sender_id',
        'message',
    ];

    public function help()
    {
        return $this->belongsTo(help::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
