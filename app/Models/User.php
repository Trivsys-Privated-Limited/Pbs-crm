<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // 'password' => 'hashed',
        ];
    }

    public function customer()
    {
        return $this->hasMany(customer::class);
    }

    public function oldCustomer()
    {
        return $this->hasMany(oldCustomer::class);
    }

    public function customerNumber()
    {
        return $this->hasMany(customerNumber::class);
    }

    public function getSalesCountForMonth($month, $year)
    {
        $oldCustomer = customer::getSalesByAgentAndMonth($this->id, $month, $year);
        $newCustomer = oldCustomer::getSalesByAgentAndMonth($this->id, $month, $year);
        $sale        = $oldCustomer + $newCustomer;

        return $sale;
    }

    public function employe()
    {
        return $this->hasOne(employe::class,'employe_id');
    }

    // public function advance()
    // {
    //     return $this->hasMany(advance::class,'employe_id');
    // }
}
