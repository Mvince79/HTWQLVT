<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Events\OrderChanged;

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
        static::created(
            fn(Order $order) =>
            OrderChanged::dispatch($order->id, 'created')
        );

        static::updated(
            fn(Order $order) =>
            OrderChanged::dispatch($order->id, 'updated')
        );

        static::deleted(
            fn(Order $order) =>
            OrderChanged::dispatch($order->id, 'deleted')
        );
    }
}
