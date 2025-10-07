<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'phone',
        'name',
        'first_name',
        'last_name',
        'nationality',
        'sex',
        'birthday',
        'locale',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthday' => 'date',
        'is_active' => 'boolean',
        'is_staff' => 'boolean',
    ];

    protected $attributes = [
        'locale' => 'en',
        'is_active' => 1,
        'is_staff' => 0,
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function authMethods()
    {
        return $this->hasMany(AuthMethod::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function eventAttendance()
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function offerRedemptions()
    {
        return $this->hasMany(OfferRedemption::class);
    }

    public function rewardRedemptions()
    {
        return $this->hasMany(RewardRedemption::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function userInterests()
    {
        return $this->hasMany(UserInterest::class);
    }
}
