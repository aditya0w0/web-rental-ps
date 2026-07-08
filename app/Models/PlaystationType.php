<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaystationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'rental_price_per_hour',
        'rental_price_per_day',
        'image',
        'is_active'
    ];

    protected $casts = [
        'rental_price_per_hour' => 'decimal:2',
        'rental_price_per_day' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function units(): HasMany
    {
        return $this->hasMany(PlaystationUnit::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function getAvailableUnitsAttribute(): int
    {
        return $this->units()->where('status', 'available')->count();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }

        return $this->image ? asset('storage/' . $this->image) : asset('images/products/playstation-5.png');
    }
}
