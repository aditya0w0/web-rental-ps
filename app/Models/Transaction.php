<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'type',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_proof',
        'payment_date',
        'pickup_method',
        'delivery_address',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_code)) {
                $transaction->transaction_code = 'TRX-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function canUploadPaymentProof(): bool
    {
        return $this->isPending() && !$this->payment_proof;
    }

    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_date' => now()
        ]);
    }

    public function getPaymentStatusTextAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
            default => $this->payment_status
        };
    }

    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'rental' => 'Penyewaan',
            'accessory_purchase' => 'Pembelian Aksesoris',
            'combined' => 'Penyewaan + Aksesoris',
            default => $this->type
        };
    }
}