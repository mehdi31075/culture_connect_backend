<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthMethod extends Model
{
    use HasFactory;

    const PROVIDER_EMAIL = 'email';
    const PROVIDER_PHONE = 'phone';
    const PROVIDER_GOOGLE = 'google';
    const PROVIDER_FACEBOOK = 'facebook';
    const PROVIDER_INSTAGRAM = 'instagram';

    protected $fillable = [
        'user_id',
        'provider',
        'identifier',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function otps()
    {
        return $this->hasMany(OtpCode::class);
    }

    public static function getProviders()
    {
        return [
            self::PROVIDER_EMAIL => 'Email',
            self::PROVIDER_PHONE => 'Phone',
            self::PROVIDER_GOOGLE => 'Google',
            self::PROVIDER_FACEBOOK => 'Facebook',
            self::PROVIDER_INSTAGRAM => 'Instagram',
        ];
    }
}
