<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'playstation_unit_id',
        'playstation_type_id',
        'start_time',
        'end_time',
        'duration_type',
        'duration_value',
        'total_price',
        'status',
        'fulfillment_status',
        'pickup_method',
        'delivery_address',
        'delivery_city',
        'delivery_distance_km',
        'delivery_fee',
        'phone_number',
        'notes',
        'payment_proof',
        'payment_proof_original_name',
        'payment_proof_provider',
        'payment_proof_confidence',
        'payment_proof_risk',
        'payment_proof_flags',
        'payment_proof_analyzed_at',
        'payment_date'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_price' => 'decimal:2',
        'payment_date' => 'datetime',
        'payment_proof_flags' => 'array',
        'payment_proof_analyzed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(PlaystationUnit::class, 'playstation_unit_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PlaystationType::class, 'playstation_type_id');
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(RentalAccessory::class);
    }

    public function transaction()
    {
        return $this->morphOne(TransactionItem::class, 'item');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function getDurationTextAttribute(): string
    {
        return $this->duration_value . ' ' . ($this->duration_type === 'hour' ? 'jam' : 'hari');
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status
        };
    }
}
