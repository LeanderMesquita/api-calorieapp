<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'food_name',
        'calories',
        'user_id',
        'meal_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
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
