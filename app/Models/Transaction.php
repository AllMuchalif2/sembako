<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Payment method constants
    const PAYMENT_METHOD_MIDTRANS = 'midtrans';
    const PAYMENT_METHOD_COD = 'cod';

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
        'payment_method',
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
