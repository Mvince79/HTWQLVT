<div x-data="{ activeTab: 'home', showFuelModal: false, showIncidentModal: false, fuelAmount: @entangle('fuelAmount'), fuelNote: @entangle('fuelNote'), incidentNote: @entangle('incidentNote'), incidentType: @entangle('incidentType'), messageReply: @entangle('messageReply'), }"
    class="max-w-md mx-auto bg-gray-100 min-h-screen pb-20 font-sans"> {{-- TOP BAR --}} <div
        class="bg-gradient-to-r from-blue-700 to-blue-500 p-4 text-white flex justify-between items-center">
        <div>
            <p class="text-xs opacity-70">Xin chào,</p>
            <p class="font-bold text-lg">{{ $driver?->user?->name ?? 'Tài xế' }}</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-white/20 px-3 py-1 rounded-lg text-sm font-semibold">
                Đăng xuất
            </button>
        </form>
    </div> {{-- CONTENT --}} <div class="p-4 space-y-4"> {{-- ACTIVE TRIP CARD --}} @if($currentTrip)
        <div class="bg-gradient-to-br from-blue-600 to-blue-400 text-white p-5 rounded-2xl shadow-lg">
            <p class="text-xs font-mono mb-3">{{ $currentTrip->id }}</p>
            <div class="space-y-3 mb-4">
                <div class="flex items-start gap-2"> <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10a3 3 0 100-6 3 3 0 000 6z" />
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z" />
                    </svg>
                    <div>
                        <p class="text-xs opacity-70">Điểm lấy hàng</p>
                        <p class="font-semibold text-sm">{{ $currentTrip->order?->pickup_address ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-2"> <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0L6.343 16.657A8 8 0 1117.657 16.657z" />
                        <circle cx="12" cy="11" r="3" stroke-width="2" />
                    </svg>
                    <div>
                        <p class="text-xs opacity-70">Điểm giao hàng</p>
                        <p class="font-semibold text-sm">{{ $currentTrip->order?->delivery_address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="flex gap-2 flex-wrap text-xs mb-4">
                <div class="bg-white/20 px-2 py-1 rounded"> <span class="opacity-70">Xe: </span> <span
                        class="font-semibold">{{ $currentTrip->vehicle?->license_plate ?? 'N/A' }}</span> </div>
                <div class="bg-white/20 px-2 py-1 rounded"> <span class="opacity-70">Trạng thái: </span> <span
                        class="font-semibold">{{ $currentTrip->status }}</span> </div>
            </div>
            <div class="flex gap-2"> <button @click="showFuelModal = true"
                    class="flex-1 bg-white text-blue-600 py-2 rounded-lg font-bold text-sm flex items-center justify-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-width="2" d="M3 3h10v18H3zM13 7h4l2 2v10h-6z" />
                    </svg> Nhiên liệu </button> <button @click="showIncidentModal = true"
                    class="flex-1 bg-amber-400 text-white py-2 rounded-lg font-bold text-sm flex items-center justify-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01M5 19h14l-7-14-7 14z" />
                    </svg> Sự cố </button> </div>
        </div> {{-- STATUS UPDATE --}} <div class="bg-white rounded-2xl p-4 shadow-sm">
            <p class="text-sm font-bold text-gray-700 mb-3">Cập nhật trạng thái chuyến</p>
            <div class="grid grid-cols-2 gap-2 mb-3">
                @foreach(['not_started' => 'Chưa bắt đầu', 'in_progress' => 'Đang chạy', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã huỷ'] as $val => $label)
                    <button wire:click="updateTripStatus('{{ $val }}')"
                        class="p-2 border-2 rounded-lg text-xs font-bold transition-all {{ $currentTrip->status === $val ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600' }}">
                {{ $label }} </button> @endforeach
            </div>
    </div> @else <div class="bg-white rounded-2xl p-6 text-center text-gray-400 shadow-sm">
            <p class="text-sm">Không có chuyến đang hoạt động</p>
        </div> @endif {{-- STATS --}} <div class="grid grid-cols-2 gap-2">
            <div class="bg-white p-4 rounded-xl shadow-sm text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $todayTripsCount }}</p>
                <p class="text-xs text-gray-500">Chuyến hôm nay</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $weekTripsCount }}</p>
                <p class="text-xs text-gray-500">Chuyến tuần này</p>
            </div>
        </div> {{-- VEHICLE INFO --}} @if($driver?->vehicle)
            <div class="bg-gray-900 text-white rounded-2xl p-4 flex items-center gap-3 shadow-md">
                <div
                    class="bg-white text-gray-900 font-mono font-bold px-3 py-2 rounded-lg text-sm tracking-widest border-2 border-gray-300">
                    {{ $driver->vehicle->license_plate }}
                </div>
                <div>
                    <p class="text-xs text-white/60">Loại xe</p>
                    <p class="font-bold">{{ $driver->vehicle->type ?? 'N/A' }}</p>
                </div>
        </div> @endif {{-- TRIP LIST --}} @if($trips->count())
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Lịch sử chuyến</p>
                @foreach($trips->take(5) as $trip) <div
                    class="bg-white rounded-xl p-3 mb-2 shadow-sm border-l-4 {{ $trip->status === 'in_progress' ? 'border-blue-500' : ($trip->status === 'completed' ? 'border-green-500' : 'border-gray-300') }}">
                    <div class="flex justify-between items-center"> <span
                            class="text-xs font-mono font-bold text-blue-600">#{{ $trip->id }}</span> <span
                            class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $trip->status === 'completed' ? 'bg-green-50 text-green-700' : ($trip->status === 'in_progress' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ $trip->status }} </span> </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $trip->created_at->format('d/m/Y H:i') }}</p>
                </div> @endforeach
        </div> @endif
    </div> {{-- BOTTOM NAV --}} <div
        class="fixed bottom-0 w-full max-w-md bg-white border-t flex justify-around py-2 z-40"> <button
            class="text-blue-600 flex flex-col items-center text-xs gap-1"> <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" d="M3 12l2-2 7-7 7 7 2 2v8a1 1 0 01-1 1h-4v-6H8v6H4a1 1 0 01-1-1z" />
            </svg> Trang chủ </button> <button class="flex flex-col items-center text-xs text-gray-500 gap-1"> <svg
                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
            </svg> Chuyến </button> <button class="flex flex-col items-center text-xs text-gray-500 gap-1"> <svg
                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-width="2" d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5z" />
                <path stroke-width="2" d="M3 22c0-4 4-7 9-7s9 3 9 7" />
            </svg> Tài khoản </button> </div> {{-- FUEL MODAL --}} <div x-show="showFuelModal" x-cloak
        class="fixed inset-0 bg-black/50 z-50 flex items-end justify-center" @click.self="showFuelModal = false">
        <div class="bg-white rounded-t-3xl w-full max-w-md p-6 pb-8">
            <div class="w-10 h-1 bg-gray-200 rounded mx-auto mb-4"></div>
            <p class="text-lg font-bold mb-4">Nhập nhiên liệu</p>
            <div class="mb-3"> <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Số lít (L)</label>
                <input type="number" wire:model="fuelAmount" x-model="fuelAmount"
                    class="w-full border-2 border-gray-200 rounded-lg p-3 text-2xl font-bold text-center focus:border-blue-500 focus:outline-none"
                    placeholder="0.0" min="0" step="0.5"> @error('fuelAmount') <p class="text-red-500 text-xs mt-1">
                        {{ $message }}
                    </p> @enderror
            </div>
            <div class="mb-4"> <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Ghi chú</label>
                <textarea wire:model="fuelNote" x-model="fuelNote"
                    class="w-full border-2 border-gray-200 rounded-lg p-3 text-sm focus:border-blue-500 focus:outline-none resize-none h-20"
                    placeholder="Cây xăng, đặc điểm..."></textarea>
            </div> <button wire:click="logFuel" @click="showFuelModal = false"
                class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold text-sm">
                Xác nhận </button> <button @click="showFuelModal = false"
                class="w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-bold text-sm mt-2"> Hủy </button>
        </div>
    </div> {{-- INCIDENT MODAL --}} <div x-show="showIncidentModal" x-cloak
        class="fixed inset-0 bg-black/50 z-50 flex items-end justify-center" @click.self="showIncidentModal = false">
        <div class="bg-white rounded-t-3xl w-full max-w-md p-6 pb-8">
            <div class="w-10 h-1 bg-gray-200 rounded mx-auto mb-4"></div>
            <p class="text-lg font-bold mb-4">Báo cáo sự cố</p>
            <div class="mb-3"> <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Loại sự cố</label>
                <select wire:model="incidentType" x-model="incidentType"
                    class="w-full border-2 border-gray-200 rounded-lg p-3 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">-- Chọn loại --</option>
                    <option>Hỏng xe / cần hỗ trợ kỹ thuật</option>
                    <option>Tai nạn giao thông</option>
                    <option>Kẹt xe / chậm tiến độ</option>
                    <option>Vấn đề với hàng hoá</option>
                    <option>Sự cố khác</option>
                </select>
            </div>
            <div class="mb-4"> <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Mô tả</label>
                <textarea wire:model="incidentNote" x-model="incidentNote"
                    class="w-full border-2 border-gray-200 rounded-lg p-3 text-sm focus:border-blue-500 focus:outline-none resize-none h-24"
                    placeholder="Vị trí, tình trạng cụ thể..."></textarea> @error('incidentNote') <p
                    class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div> <button wire:click="reportIncident" @click="showIncidentModal = false"
                class="w-full bg-red-500 text-white py-3 rounded-xl font-bold text-sm"> Gửi báo cáo </button> <button
                @click="showIncidentModal = false"
                class="w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-bold text-sm mt-2"> Hủy </button>
        </div>
    </div>
</div>