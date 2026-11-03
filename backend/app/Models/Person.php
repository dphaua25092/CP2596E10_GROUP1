<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// Diễn viên, đạo diễn
class Person extends Model
{
    protected $table = 'people';

    public $timestamps = false;

    protected $fillable = ['full_name', 'photo_url', 'bio'];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_credits')
            ->withPivot('role', 'display_order');
    }
}
