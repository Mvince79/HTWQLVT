<?php

namespace App\Models;

use Carbon\Carbon;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model implements Eventable
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory;

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function toCalendarEvent(): CalendarEvent
    {
        [$bgColor, $textColor] = match (true) {
            $this->status === 'completed' => ['#3b6d11', '#eaf3de'],
            $this->status === 'cancelled' => ['#e24b4a', '#ffffff'],
            $this->status === 'in_progress' => ['#f97316', '#ffffff'],
            default => ['#378add', '#ffffff'],
        };

        $start = $this->start_time ? Carbon::parse($this->start_time) : now();
        $end = $this->end_time ? Carbon::parse($this->end_time) : $start;

        $driverName = $this->driver?->user?->name ?? ('Tài xế #' . $this->driver_id);

        $title = match (true) {
            !$this->start_time => '#' . $driverName,
            $this->start_time && !$this->end_time => $start->format('H:i') . ' #' . $driverName,
            default => $start->format('H:i') . ' - ' . $end->format('H:i') . ' #' . $driverName,
        };

        return CalendarEvent::make($this)
            ->title($title)
            ->start($start->copy()->startOfDay())
            ->end($end->copy()->startOfDay())
            ->backgroundColor($bgColor)
            ->textColor($textColor)
            ->allDay();
    }
}
