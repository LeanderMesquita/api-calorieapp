<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meal extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'entries_limit',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function scopeForUser(Builder $query, User $user)
    {
        if($user->isAdmin())
        {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }
}
