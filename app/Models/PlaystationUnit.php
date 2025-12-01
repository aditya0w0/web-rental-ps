<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaystationUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'playstation_type_id',
        'unit_code',
        'serial_number',
        'status',
        'condition_notes'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(PlaystationType::class, 'playstation_type_id');
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isRented(): bool
    {
        return $this->status === 'rented';
    }

    public function markAsRented(): void
    {
        $this->update(['status' => 'rented']);
    }

    public function markAsAvailable(): void
    {
        $this->update(['status' => 'available']);
    }
}