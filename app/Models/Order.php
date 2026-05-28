<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'shipping_cost',
        'status',
        'fulfillment_status',
        'payment_proof',
        'payment_proof_original_name',
        'payment_proof_provider',
        'payment_proof_confidence',
        'payment_proof_risk',
        'payment_proof_flags',
        'payment_proof_analyzed_at',
        'payment_date',
        'pickup_method',
        'delivery_address',
        'delivery_city',
        'delivery_district',
        'rejection_reason',
        'tracking_number',
        'delivered_at',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'delivered_at' => 'datetime',
        'payment_proof_flags' => 'array',
        'payment_proof_analyzed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function issues()
    {
        return $this->hasMany(OrderIssue::class);
    }
}
