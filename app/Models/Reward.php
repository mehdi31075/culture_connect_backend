<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'points_required',
    ];

    protected $casts = [
        'points_required' => 'integer',
    ];

    public function redemptions()
    {
        return $this->hasMany(RewardRedemption::class);
    }
}
