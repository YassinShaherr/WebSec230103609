<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'credit',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'password' => 'hashed',
            'credit' => 'decimal:2',
        ];
    }

    /**
     * Add credit to user's account
     *
     * @param float $amount
     * @return void
     */
    public function addCredit(float $amount)
    {
        $this->credit += $amount;
        $this->save();
    }

    /**
     * Use credit from user's account
     *
     * @param float $amount
     * @return bool
     */
    public function useCredit(float $amount)
    {
        if ($this->credit >= $amount) {
            $this->credit -= $amount;
            $this->save();
            return true;
        }
        return false;
    }
}
