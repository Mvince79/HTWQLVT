<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\Order;
use App\Models\Incident;
use App\Models\FuelLog;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    // ── Trip ────────────────────────────────────────────

    public static function tripUpdated(Trip $trip): void
    {
        $statusLabel = self::tripStatus($trip->status);
        $driverName = $trip->driver?->user?->name ?? '—';
        $plate = $trip->vehicle?->license_plate ?? '—';

        // Tới admin panel (Filament broadcast)
        broadcast(new \App\Events\TripUpdated($trip))->toOthers();

        // Email chỉ khi hoàn thành hoặc huỷ
        if (in_array($trip->status, ['completed', 'cancelled'])) {
            // optional: Notification::route('mail', config('mail.admin'))->notify(...)
        }
    }

    public static function tripAssigned(Trip $trip): void
    {
        broadcast(new \App\Events\TripAssigned($trip));
    }

    // ── Order ───────────────────────────────────────────

    public static function orderUpdated(Order $order): void
    {
        broadcast(new \App\Events\OrderUpdated(
            $order->id,
            $order->status,
            $order->code
        ));
    }

    // ── Incident ────────────────────────────────────────

    public static function incidentReported(Incident $incident): void
    {
        broadcast(new \App\Events\IncidentReported($incident));

        // Email admin — thay vì để trống
        Notification::route('mail', config('mail.ops_email'))->notify(new \App\Notifications\NewIncidentReported($incident));
    }

    // ── Fuel ─────────────────────────────────────────────

    public static function fuelLogged(FuelLog $fuelLog): void
    {
        broadcast(new \App\Events\FuelLogCreated($fuelLog));
    }

    // ── Helpers ──────────────────────────────────────────

    public static function tripStatus(string $s): string
    {
        return match ($s) {
            'not_started' => 'Chờ khởi hành',
            'in_progress' => 'Đang chạy',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã huỷ',
            default => $s,
        };
    }

    public static function orderStatus(string $s): string
    {
        return match ($s) {
            'pending' => 'Chờ xử lý',
            'assigned' => 'Đã phân công',
            'delivering' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã huỷ',
            default => $s,
        };
    }
}