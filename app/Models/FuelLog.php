<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelLog extends Model
{
    use HasFactory;

    protected $table = 'fuel_logs';

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'trip_id',
        'fuel_amount',
        'note',
    ];

    protected $casts = [
        'fuel_amount' => 'decimal:2',
    ];

    public const UPDATED_AT = null;



    protected $dispatchesEvents = [
        'created' => \App\Events\FuelLogCreated::class,
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
