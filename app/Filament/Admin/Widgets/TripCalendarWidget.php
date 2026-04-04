<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Collection;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Support\HtmlString;

class TripCalendarWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;
    protected bool $eventClickEnabled = true;
    protected ?string $defaultEventClickAction = 'edit';
    protected function getListeners(): array
    {
        return [
            'echo:trips,.trip.changed' => 'refreshCalendar',
        ];
    }

    public function refreshCalendar(): void
    {
        $this->dispatch('calendar--refresh');
    }

    protected function getEvents(FetchInfo $info): Collection|array|Builder
    {
        return Trip::query()
            ->with(['driver.user'])
            ->whereDate('start_time', '<=', $info->end)
            ->where(function ($q) use ($info) {
                $q->whereNull('end_time')
                    ->orWhereDate('end_time', '>=', $info->start);
            })
            ->get();
    }

    public function getHeading(): string|HtmlString
    {
        return new HtmlString(
            view('filament.admin.widgets.trip-calendar-widget')->render()
        );
    }
}
