<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'cargo_name',
        'cargo_weight',
        'price',
        'pickup_location',
        'delivery_location',
        'scheduled_date',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            // user_id
            $order->user_id = auth()->id();

            // code auto
            $order->code = 'ORD-' . now()->format('Ymd') . '-' . rand(100, 999);
        });

        static::updating(function ($order) {
            // nếu chuyển sang completed → set scheduled_date
            if ($order->status === 'completed' && !$order->scheduled_date) {
                $order->scheduled_date = now();
            }
        });
    }
}
