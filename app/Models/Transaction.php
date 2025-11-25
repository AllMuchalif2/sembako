<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'total_amount',
        'promo_code',
        'discount_amount',
        // 'payment_status',
        'status',
        'shipping_address',
        'latitude',
        'longitude',
        'distance_from_store',
        'shipping_cost',
        'notes',
        'snap_token',
        // 'payment_type'
    ];

    public function getRouteKeyName()
    {
        return 'order_id';
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function promoUsages()
    {
        return $this->hasMany(PromoUsage::class);
    }
}
