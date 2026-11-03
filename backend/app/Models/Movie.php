<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'synopsis',
        'duration_minutes',
        'language_id',
        'age_rating',
        'release_date',
        'end_date',
        'poster_url',
        'trailer_url',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'release_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
            'is_featured' => 'boolean',
        ];
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'movie_credits')
            ->withPivot('role', 'display_order')
            ->orderByPivot('display_order');
    }

    public function actors(): BelongsToMany
    {
        return $this->people()->wherePivot('role', 'actor');
    }

    public function directors(): BelongsToMany
    {
        return $this->people()->wherePivot('role', 'director');
    }

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Đang chiếu: đã khởi chiếu và chưa hết hạn chiếu
    public function scopeNowShowing(Builder $query): void
    {
        $today = today();
        $query->whereDate('release_date', '<=', $today)
            ->where(fn (Builder $q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today));
    }

    public function scopeComingSoon(Builder $query): void
    {
        $query->whereDate('release_date', '>', today());
    }
}
