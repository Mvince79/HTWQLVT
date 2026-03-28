<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
