<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'max_discount',
        'min_purchase',
        'start_date',
        'end_date',
        'usage_limit',
        'times_used',
        'limit_per_user',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'limit_per_user' => 'boolean',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(PromoUsage::class);
    }
}