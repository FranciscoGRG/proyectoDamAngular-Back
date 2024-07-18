<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'distance', 'unevenness', 'difficulty',
        'mapsIFrame', 'location', 'imagen', 'fecha', 'hora', 'category', 'likes', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Realcion muchos a muchos
    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'route_likes');
    }

    //Realcion muchos a muchos
    public function subscribedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'route_user');
    }
}
