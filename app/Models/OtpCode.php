<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'auth_method_id',
        'code',
        'expires_at',
        'attempts',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'attempts' => 'integer',
        'is_used' => 'boolean',
    ];

    protected $attributes = [
        'attempts' => 0,
        'is_used' => false,
    ];

    public function authMethod()
    {
        return $this->belongsTo(AuthMethod::class);
    }

    public function isValid(): bool
    {
        return !$this->is_used && Carbon::now()->lte($this->expires_at);
    }
}
