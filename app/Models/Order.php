<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Events\OrderUpdated;

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

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->code)) {
                $order->code = 'ORD-' . strtoupper(substr(uniqid(), -8));
            }
        });

        static::updated(function (Order $order) {
            if ($order->wasChanged('status')) {
                event(new OrderUpdated($order->id, $order->status, $order->code));
            }
        });
    }
}
