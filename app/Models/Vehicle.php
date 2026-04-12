<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';

    // ================= FILLABLE =================
    protected $fillable = [
        'license_plate',
        'type',
        'is_active',
        'working_status',
    ];
    // ================= CAST =================
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ================= CONSTANTS =================

    public const TYPE = [
        'light_truck' => 'Xe tải nhẹ',
        'medium_truck' => 'Xe tải trung',
        'heavy_truck' => 'Xe tải nặng',
        'container_truck' => 'Xe container',
    ];

    public const STATUS = [
        'available' => 'Sẵn sàng',
        'in_use' => 'Đang sử dụng',
        'maintenance' => 'Đang bảo trì',
    ];

    // ================= RELATIONSHIPS =================

    /**
     * 1 xe có nhiều chuyến
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * 1 xe có nhiều log nhiên liệu
     */
    public function fuelLogs(): HasMany
    {
        return $this->hasMany(\App\Models\FuelLog::class);
    }

    public function markAsAvailable(): void
    {
        $this->update(['working_status' => 'available']);
    }

    // ================= SCOPES =================

    /**
     * Xe đang sẵn sàng
     */
    public function scopeAvailable(Builder $query)
    {
        return $query->where('working_status', 'available');
    }

    /**
     * Xe đang sử dụng
     */
    public function scopeInUse(Builder $query)
    {
        return $query->where('working_status', 'in_use');
    }

    /**
     * Xe đang bảo trì
     */
    public function scopeMaintenance(Builder $query)
    {
        return $query->where('working_status', 'maintenance');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // ================= HELPERS =================

    public function isAvailable(): bool
    {
        return $this->working_status === 'available';
    }

    public function isInUse(): bool
    {
        return $this->working_status === 'in_use';
    }

    public function isMaintenance(): bool
    {
        return $this->working_status === 'maintenance';
    }

    // ================= BUSINESS LOGIC =================

    /**
     * Đánh dấu xe đang sử dụng
     */
    public function markAsInUse(): void
    {
        $this->update(['working_status' => 'in_use']);
    }



    /**
     * Đánh dấu xe bảo trì
     */
    public function markAsMaintenance(): void
    {
        $this->update(['working_status' => 'maintenance']);
    }

    // ================= ACCESSORS =================

    /**
     * Hiển thị loại xe dạng text
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPE[$this->type] ?? $this->type;
    }

    /**
     * Hiển thị trạng thái dạng text
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->working_status] ?? $this->working_status;
    }
}
