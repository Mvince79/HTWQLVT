<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'license_number',// số bằng lái giấy phép lái xe
        'license_type',// B2, C, FC
        'license_expiry',// ngày hết hạn bằng lái
        'status',
        'working_status',
    ];

    protected $casts = [
        'license_expiry' => 'date',
    ];

    public function getPhoneAttribute($value): string
    {
        if (empty($value))
            return 'Chưa có SĐT';

        // Bỏ tất cả ký tự không phải số
        $phone = preg_replace('/[^0-9]/', '', $value);

        // Chuẩn hóa đầu số
        if (str_starts_with($phone, '84')) {
            $phone = '0' . substr($phone, 2);
        }

        return $phone;
    }
    // ================= RELATIONSHIPS =================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function fuelLogs(): HasMany
    {
        return $this->hasMany(FuelLog::class);
    }

    /**
     * Các xe tài xế đã từng lái (qua trips)
     */
    public function vehicles(): HasManyThrough
    {
        return $this->hasManyThrough(
            Vehicle::class,
            Trip::class,
            'driver_id',  // FK trên trips
            'id',         // FK trên vehicles
            'id',         // PK trên drivers
            'vehicle_id'  // FK trên trips trỏ tới vehicles
        );
    }

    // ================= HELPERS =================

    /**
     * Xe đang lái hiện tại (trip in_progress)
     */
    public function currentVehicle(): ?Vehicle
    {
        $trip = $this->trips()
            ->with('vehicle')
            ->whereIn('status', ['not_started', 'in_progress'])
            ->latest()
            ->first();

        return $trip?->vehicle;
    }

    /**
     * Kiểm tra tài xế có thể lái loại xe này không (dựa theo hạng bằng)
     */
    public function canDrive(Vehicle $vehicle): bool
    {
        return match ($this->license_type) {
            'FC' => true, // lái được tất cả
            'C' => in_array($vehicle->type, ['light_truck', 'medium_truck', 'heavy_truck']),
            'B2' => $vehicle->type === 'light_truck',
            default => false,
        };
    }
}