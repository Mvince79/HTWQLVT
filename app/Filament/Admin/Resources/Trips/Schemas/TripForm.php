<?php

namespace App\Filament\Admin\Resources\Trips\Schemas;

use App\Models\Order;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Services\TripConflictValidator;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

use function now;

class TripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Điều phối vận hành (Operational Dispatch)')
                ->description('Thiết lập lộ trình và thời gian vận chuyển')
                ->schema([

                    // ── 1. Thời gian khởi hành ──────────────────────────────────────
                    DateTimePicker::make('start_time')
                        ->label('Thời gian khởi hành')
                        ->default(now('Asia/Ho_Chi_Minh'))
                        ->native(false)
                        ->required()
                        ->live()
                        ->timezone('Asia/Ho_Chi_Minh')
                        ->displayFormat('d/m/Y H:i')
                        // Create: không chọn quá khứ. Edit: admin được phép sửa tự do
                        ->minDate(
                            fn(string $operation) =>
                            $operation === 'create' ? now('Asia/Ho_Chi_Minh') : null
                        )
                        // Không vượt deadline đơn hàng
                        ->maxDate(function (Get $get) {
                            $d = $get('_order_deadline');
                            return $d ? Carbon::parse($d) : null;
                        })
                        ->afterStateUpdated(function (Set $set) {
                            // Thay đổi khung giờ → reset để buộc chọn lại, tránh conflict ẩn
                            $set('driver_id', null);
                            $set('vehicle_id', null);
                        }),

                    // ── 2. ETA ──────────────────────────────────────────────────────
                    DateTimePicker::make('end_time')
                        ->label('Thời gian đến dự kiến (ETA)')
                        ->native(false)
                        ->required()
                        ->live()
                        ->timezone('Asia/Ho_Chi_Minh')
                        ->displayFormat('d/m/Y H:i')
                        // ETA phải sau start_time ít nhất 1 phút
                        ->minDate(
                            fn(Get $get) =>
                            $get('start_time')
                            ? Carbon::parse($get('start_time'))->addMinute()
                            : now('Asia/Ho_Chi_Minh')
                        )
                        // Không vượt deadline đơn hàng
                        ->maxDate(function (Get $get) {
                            $d = $get('_order_deadline');
                            return $d ? Carbon::parse($d) : null;
                        })
                        ->after('start_time')
                        ->helperText(function (Get $get) {
                            $d = $get('_order_deadline');
                            return $d
                                ? 'Phải hoàn thành trước ' . Carbon::parse($d)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i')
                                : 'Chọn đơn hàng để xem deadline giao.';
                        })
                        ->afterStateUpdated(function (Set $set) {
                            $set('driver_id', null);
                            $set('vehicle_id', null);
                        }),

                    // ── 3. Đơn hàng ─────────────────────────────────────────────────
                    Select::make('order_id')
                        ->label('Mã vận đơn / Đơn hàng')
                        ->options(function (Get $get) {
                            $currentOrderId = $get('order_id');

                            // Đơn đã gán cho chuyến active
                            $assignedOrderIds = \App\Models\Trip::whereNotIn('status', ['completed', 'cancelled'])
                                ->pluck('order_id')
                                ->filter()
                                ->toArray();

                            // Loại đơn đang edit ra khỏi danh sách bị chặn
                            // (để đơn của chuyến này vẫn hiện trong dropdown khi edit)
                            if ($currentOrderId) {
                                $assignedOrderIds = array_diff($assignedOrderIds, [(int) $currentOrderId]);
                            }

                            return Order::whereNotIn('id', $assignedOrderIds)
                                ->whereNotIn('status', ['completed', 'cancelled'])
                                ->orderByDesc('created_at')
                                ->get()
                                // Hiển thị MÃ ĐƠN (code), không phải database ID
                                ->mapWithKeys(fn($o) => [
                                    $o->id => $o->code . ' — ' . $o->cargo_name,
                                ]);
                        })
                        // Render label cho giá trị đã được lưu (khi load trang edit)
                        ->getOptionLabelUsing(function ($value) {
                            $o = Order::find($value);
                            return $o ? $o->code . ' — ' . $o->cargo_name : $value;
                        })
                        ->searchable()
                        ->required()
                        ->live()
                        ->helperText('Chỉ hiển thị đơn chưa được phân công & chưa hoàn thành')
                        ->afterStateUpdated(function ($state, Set $set) {
                            $order = Order::find($state);
                            // Lưu deadline vào hidden field để date pickers dùng làm maxDate
                            $set('_order_deadline', $order?->scheduled_date?->toDateTimeString());
                        })
                        ->suffixAction(
                            Action::make('create_order_inline')
                                ->label('Tạo đơn mới')
                                ->icon('heroicon-o-plus-circle')
                                ->color('gray')
                                ->form([
                                    TextInput::make('cargo_name')
                                        ->label('Tên hàng hóa')
                                        ->required(),

                                    TextInput::make('cargo_weight')
                                        ->label('Trọng lượng (kg)')
                                        ->numeric()
                                        ->minValue(0.1)
                                        ->required(),

                                    TextInput::make('price')
                                        ->label('Giá trị đơn (VND)')
                                        ->numeric(),

                                    DateTimePicker::make('scheduled_date')
                                        ->label('Deadline giao hàng')
                                        ->native(false)
                                        ->timezone('Asia/Ho_Chi_Minh')
                                        ->displayFormat('d/m/Y H:i')
                                        ->minDate(now('Asia/Ho_Chi_Minh')->addHours(2))
                                        ->required()
                                        ->helperText('Tối thiểu 2 giờ kể từ bây giờ.'),

                                    Textarea::make('pickup_location')
                                        ->label('Điểm lấy hàng')
                                        ->required()
                                        ->rows(2),

                                    Textarea::make('delivery_location')
                                        ->label('Điểm giao hàng')
                                        ->required()
                                        ->rows(2),
                                ])
                                ->action(function (array $data, Set $set) {
                                    $order = Order::create([
                                        ...$data,
                                        'user_id' => Auth::id(),
                                        'status' => 'pending',
                                    ]);
                                    $set('order_id', $order->id);
                                    $set('_order_deadline', $order->scheduled_date?->toDateTimeString());
                                })
                        ),

                    // ── 4. Tài xế — lọc conflict theo khung giờ ─────────────────────
                    Select::make('driver_id')
                        ->label('Tài xế phụ trách')
                        ->options(function (Get $get) {
                            $start = $get('start_time') ? Carbon::parse($get('start_time')) : null;
                            $end = $get('end_time') ? Carbon::parse($get('end_time')) : null;
                            $currentDriverId = $get('driver_id');
                            $currentTripId = $get('id') ? (int) $get('id') : null;

                            // Tài xế đang có chuyến overlap với khung giờ này
                            // Chuẩn Allen's interval overlap: A.start < B.end AND A.end > B.start
                            $busyDriverIds = [];
                            if ($start && $end) {
                                $busyDriverIds = \App\Models\Trip::whereNotIn('status', ['completed', 'cancelled'])
                                    ->when($currentTripId, fn($q) => $q->where('id', '!=', $currentTripId))
                                    ->where('start_time', '<', $end)
                                    ->where('end_time', '>', $start)
                                    ->pluck('driver_id')
                                    ->filter()
                                    ->toArray();
                            }

                            return Driver::where(function ($q) use ($busyDriverIds, $currentDriverId) {
                                // Tài xế rảnh HOẶC chính tài xế đang assign (để label hiện đúng khi edit)
                                $q->whereNotIn('id', $busyDriverIds);
                                if ($currentDriverId) {
                                    $q->orWhere('id', $currentDriverId);
                                }
                            })
                                // Chỉ tài xế đang active — dựa theo Driver.status field
                                ->whereHas('user', fn($q) => $q->where('is_active', true))
                                ->with('user')
                                ->get()
                                ->mapWithKeys(fn($d) => [
                                    $d->id => ($d->user?->name ?? '—') . ' · ' . $d->phone,
                                ]);
                        })
                        ->getOptionLabelUsing(function ($value) {
                            $d = Driver::with('user')->find($value);
                            return $d ? ($d->user?->name ?? '—') . ' · ' . $d->phone : $value;
                        })
                        ->rules([
                            fn(Get $get) => function ($attr, $value, $fail) use ($get) {
                                // Race-condition guard: validate lại lúc submit
                                TripForm::validateConflicts($get, $fail);
                            },
                        ])
                        ->searchable()
                        ->required()
                        ->live()
                        ->helperText(
                            fn(Get $get) =>
                            $get('start_time') && $get('end_time')
                            ? 'Chỉ hiển thị tài xế rảnh trong khung giờ đã chọn'
                            : 'Chọn thời gian khởi hành & ETA trước'
                        ),

                    // ── 5. Xe — lọc conflict + bảo trì ──────────────────────────────
                    Select::make('vehicle_id')
                        ->label('Xe vận chuyển')
                        ->options(function (Get $get) {
                            $start = $get('start_time') ? Carbon::parse($get('start_time')) : null;
                            $end = $get('end_time') ? Carbon::parse($get('end_time')) : null;
                            $currentVehicleId = $get('vehicle_id');
                            $currentTripId = $get('id') ? (int) $get('id') : null;

                            $busyVehicleIds = [];
                            if ($start && $end) {
                                $busyVehicleIds = \App\Models\Trip::whereNotIn('status', ['completed', 'cancelled'])
                                    ->when($currentTripId, fn($q) => $q->where('id', '!=', $currentTripId))
                                    ->where('start_time', '<', $end)
                                    ->where('end_time', '>', $start)
                                    ->pluck('vehicle_id')
                                    ->filter()
                                    ->toArray();
                            }

                            return Vehicle::where(function ($q) use ($busyVehicleIds, $currentVehicleId) {
                                $q->whereNotIn('id', $busyVehicleIds);
                                if ($currentVehicleId) {
                                    $q->orWhere('id', $currentVehicleId);
                                }
                            })
                                ->where('is_active', true)
                                // Không lấy xe đang bảo trì — dựa theo Vehicle.working_status
                                ->where('working_status', '!=', 'maintenance')
                                ->get()
                                ->mapWithKeys(fn($v) => [
                                    $v->id => $v->license_plate . ' — ' . match ($v->type) {
                                        'light_truck' => 'Xe tải nhẹ',
                                        'medium_truck' => 'Xe tải trung',
                                        'heavy_truck' => 'Xe tải nặng',
                                        'container_truck' => 'Xe container',
                                        default => $v->type,
                                    },
                                ]);
                        })
                        ->getOptionLabelUsing(function ($value) {
                            $v = Vehicle::find($value);
                            return $v
                                ? $v->license_plate . ' — ' . match ($v->type) {
                                    'light_truck' => 'Xe tải nhẹ',
                                    'medium_truck' => 'Xe tải trung',
                                    'heavy_truck' => 'Xe tải nặng',
                                    'container_truck' => 'Xe container',
                                    default => $v->type,
                                }
                                : $value;
                        })
                        ->rules([
                            fn(Get $get) => function ($attribute, $value, $fail) use ($get) {
                                TripForm::validateConflicts($get, $fail);
                            },
                        ])
                        ->searchable()
                        ->required()
                        ->live()
                        ->helperText(
                            fn(Get $get) =>
                            $get('start_time') && $get('end_time')
                            ? 'Chỉ hiển thị xe rảnh trong khung giờ đã chọn'
                            : 'Chọn thời gian khởi hành & ETA trước'
                        ),

                    // ── 6. Trạng thái ───────────────────────────────────────────────
                    Select::make('status')
                        ->columnSpan(2)
                        ->label('Trạng thái vận hành')
                        ->options([
                            'not_started' => 'Đã lên lịch (Pending)',
                            'in_progress' => 'Đang thực hiện (Active)',
                            'completed' => 'Đã về bãi (Finished)',
                            'cancelled' => 'Sự cố / Hủy (Void)',
                        ])
                        ->default('not_started')
                        ->required()
                        ->native(false),

                    // ── Hidden: lưu deadline đơn để date pickers dùng làm maxDate ───
                    \Filament\Forms\Components\Hidden::make('_order_deadline'),

                ])->columns(2),
        ])->columns(1);
    }

    /**
     * Race-condition guard (lớp 2) — chạy tại submit.
     * Bắt trường hợp 2 admin tạo chuyến cùng lúc hoặc dữ liệu thay đổi
     * sau khi dropdown đã render.
     */
    private static function validateConflicts(Get $get, \Closure $fail): void
    {
        $driverId = $get('driver_id');
        $vehicleId = $get('vehicle_id');
        $startRaw = $get('start_time');
        $endRaw = $get('end_time');

        if (!$driverId || !$vehicleId || !$startRaw || !$endRaw) {
            return;
        }

        $start = Carbon::parse($startRaw);
        $end = Carbon::parse($endRaw);

        if ($start->gte($end)) {
            return;
        }

        // Khi edit: loại trừ chính chuyến này để không self-conflict
        $recordId = $get('id') ? (int) $get('id') : null;

        $driverConflict = TripConflictValidator::driverHasConflict(
            $driverId,
            $start,
            $end,
            excludeTripId: $recordId
        );
        if ($driverConflict) {
            $s = Carbon::parse($driverConflict->start_time)->timezone('Asia/Ho_Chi_Minh')->format('d/m H:i');
            $e = Carbon::parse($driverConflict->end_time)->timezone('Asia/Ho_Chi_Minh')->format('d/m H:i');
            $fail("Tài xế đã có chuyến #{$driverConflict->id} ({$s}–{$e}). Vui lòng chọn tài xế khác.");
            return;
        }

        $vehicleConflict = TripConflictValidator::vehicleHasConflict(
            $vehicleId,
            $start,
            $end,
            excludeTripId: $recordId
        );
        if ($vehicleConflict) {
            $s = Carbon::parse($vehicleConflict->start_time)->timezone('Asia/Ho_Chi_Minh')->format('d/m H:i');
            $e = Carbon::parse($vehicleConflict->end_time)->timezone('Asia/Ho_Chi_Minh')->format('d/m H:i');
            $fail("Xe đã được phân công chuyến #{$vehicleConflict->id} ({$s}–{$e}). Vui lòng chọn xe khác.");
        }
    }
}