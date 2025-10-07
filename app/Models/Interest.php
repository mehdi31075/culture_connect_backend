<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function userInterests()
    {
        return $this->hasMany(UserInterest::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_interests');
    }
}
