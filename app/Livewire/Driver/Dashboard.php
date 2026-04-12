<?php

namespace App\Livewire\Driver;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Trip;
use App\Models\FuelLog;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Incident;

#[Layout('layouts.driver')]
class Dashboard extends Component
{
    public $activeTab = 'home';

    // Fuel modal
    public $fuelAmount = '';
    public $fuelNote = '';

    // Incident modal
    public $incidentNote = '';
    public $incidentType = '';

    // Inbox
    public $messageReply = '';
    public $selectedMessageId = null;

    // Trip status
    public $selectedStatus = '';

    // ===================== MOUNT =====================

    public function mount()
    {
        $driver = Auth::user()->driver;
        if (!$driver) {
            Auth::logout();
            return redirect('/admin/login');
        }

        $this->selectedStatus = $this->currentTrip?->status ?? '';
    }

    // ===================== COMPUTED =====================

    public function getDriverProperty()
    {
        return Auth::user()->driver;
    }

    public function getCurrentVehicleProperty(): ?Vehicle
    {
        return $this->currentTrip?->vehicle;
    }

    public function getCurrentTripProperty()
    {
        return $this->driver?->trips()
            ->whereIn('status', ['not_started', 'in_progress'])
            ->with(['vehicle', 'order.user'])
            ->latest()
            ->first();
    }

    public function getTripsProperty()
    {
        return $this->driver?->trips()
            ->with(['vehicle', 'order'])
            ->orderByDesc('created_at')
            ->take(20)
            ->get();
    }

    public function getTodayTripsCountProperty()
    {
        return $this->driver?->trips()
            ->whereDate('created_at', today())
            ->count() ?? 0;
    }

    public function getWeekTripsCountProperty()
    {
        return $this->driver?->trips()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count() ?? 0;
    }

    // ===================== ACTIONS =====================

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function logFuel(): void
    {
        $this->validate([
            'fuelAmount' => 'required|numeric|min:0.1|max:500',
            'fuelNote' => 'nullable|string|max:500',
        ], [
            'fuelAmount.required' => 'Vui lòng nhập số lít nhiên liệu.',
            'fuelAmount.numeric' => 'Số lít phải là số.',
            'fuelAmount.min' => 'Số lít phải lớn hơn 0.',
        ]);

        $trip = $this->currentTrip;

        FuelLog::create([
            'vehicle_id' => $trip?->vehicle_id,
            'driver_id' => $this->driver->id,
            'trip_id' => $trip?->id,
            'fuel_amount' => $this->fuelAmount,
            'note' => $this->fuelNote,
        ]);

        $this->reset(['fuelAmount', 'fuelNote']);
        $this->dispatch('notify', type: 'success', message: 'Đã ghi nhận nhiên liệu!');
    }

    public function updateTripStatus(string $status): void
    {
        $trip = $this->currentTrip;
        if (!$trip)
            return;

        $allowed = ['not_started', 'in_progress', 'completed', 'cancelled'];
        if (!in_array($status, $allowed))
            return;

        $trip->update(['status' => $status]);

        if ($status === 'completed' || $status === 'cancelled') {
            $trip->vehicle?->markAsAvailable();
            if ($trip->order) {
                $trip->order->update([
                    'status' => $status === 'completed' ? 'completed' : 'cancelled',
                ]);
            }
        }

        $this->selectedStatus = $status;
        $this->dispatch('notify', type: 'success', message: 'Đã cập nhật trạng thái chuyến!');
    }

    public function reportIncident(): void
    {
        $trip = $this->currentTrip;

        if (!$trip) {
            $this->dispatch('notify', type: 'error', message: 'Bạn phải đang trong một chuyến đi mới có thể báo cáo sự cố.');
            return;
        }

        $this->validate([
            'incidentType' => 'required|string',
            'incidentNote' => 'required|string|min:10',
        ]);

        $incident = Incident::create([
            'trip_id' => $trip->id,
            'driver_id' => $this->driver->id,
            'type' => $this->incidentType,
            'note' => $this->incidentNote,
        ]);

        event(new \App\Events\IncidentReported($incident));

        $this->reset(['incidentType', 'incidentNote']);
        $this->dispatch('notify', message: 'Đã gửi báo cáo sự cố.');
    }

    public function sendReply(int $msgId): void
    {
        $this->validate([
            'messageReply' => 'required|string|min:1|max:500',
        ]);

        Log::info('Driver replied', [
            'driver_id' => $this->driver->id,
            'message_id' => $msgId,
            'reply' => $this->messageReply,
        ]);

        $this->reset(['messageReply', 'selectedMessageId']);
        $this->dispatch('notify', type: 'success', message: 'Đã gửi phản hồi!');
    }

    public function markRead(int $msgId): void
    {
        Log::info('Notification marked read', ['id' => $msgId]);
    }

    // ===================== RENDER =====================

    public function render()
    {
        return view('livewire.driver.dashboard', [
            'driver' => $this->driver,
            'currentTrip' => $this->currentTrip,
            'currentVehicle' => $this->currentVehicle,
            'trips' => $this->trips,
            'todayTripsCount' => $this->todayTripsCount,
            'weekTripsCount' => $this->weekTripsCount,
        ]);
    }
}