<?php

namespace App\Models;

use App\Events\DriverChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'license_number',
        'license_type',
        'license_expiry',
        'vehicle_type',
        'plate_number',
        'working_status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    protected static function booted(): void
    {
        static::created(
            fn(Driver $driver) =>
            DriverChanged::dispatch($driver->id, 'created')
        );

        static::updated(
            fn(Driver $driver) =>
            DriverChanged::dispatch($driver->id, 'updated')
        );

        static::deleted(
            fn(Driver $driver) =>
            DriverChanged::dispatch($driver->id, 'deleted')
        );
    }
}
