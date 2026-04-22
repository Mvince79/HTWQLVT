<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VehicleOps - Driver</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    {{ $slot }}
    @livewireScripts
</body>

<script type="module">
    document.addEventListener('livewire:initialized', () => {
        const driverId = parseInt("{{ auth()->user()?->driver?->id ?? 0 }}"); function toast(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('notify', { detail: { message, type } }));
        }

        // Chỉ lắng nghe những gì liên quan tới driver này
        if (driverId > 0) {
            Echo.channel(`driver.${driverId}`)
                .listen('.trip.assigned', (e) => {
                    toast(`Chuyến mới #${e.trip_id}: ${e.pickup_location} → ${e.delivery_location}`);
                    Livewire.dispatch(`echo:driver.${driverId},.trip.assigned`, e);
                });
        }

        // Cập nhật order của chính chuyến đang chạy
        Echo.channel('orders')
            .listen('.order.updated', (e) => {
                Livewire.dispatch('echo:orders,.order.updated', e);
                // Không toast tất cả order — chỉ dispatch để Livewire tự re-render
            });

        Echo.channel('fleet-updates')
            .listen('.trip.updated', (e) => {
                Livewire.dispatch('echo:fleet-updates,.trip.updated', e);
            });
    });
</script>

</html>