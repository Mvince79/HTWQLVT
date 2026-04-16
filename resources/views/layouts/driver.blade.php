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

        const driverId = Number("{{ auth()->user()?->driver?->id ?? 0 }}");

        function notify(message, type = 'success') {
            window.dispatchEvent(
                new CustomEvent('notify', { detail: { message, type } })
            );
        }

        function dispatchToLivewire(eventName, data = {}) {
            Livewire.dispatch(eventName, data);
        }

        if (driverId > 0) {
            Echo.channel(`driver.${driverId}`)
                .listen('.trip.assigned', (e) => {
                    notify(`🚚 Chuyến mới #${e.trip_id}: ${e.pickup_location} → ${e.delivery_location}`, 'success');
                    dispatchToLivewire(`echo:driver.${driverId},.trip.assigned`, e);
                });
        }

        Echo.channel('orders')
            .listen('.order.updated', (e) => {
                notify(e.message, 'success');
                dispatchToLivewire('echo:orders,.order.updated', e);
            });

        Echo.channel('vehicle-updates')
            .listen('.vehicle.status.updated', (e) => {
                notify(`Xe ${e.license_plate} → ${e.status}`, 'info');
                dispatchToLivewire('echo:vehicle-updates,.vehicle.status.updated', e);
            });

        Echo.channel('fleet-updates')
            .listen('.trip.updated', (e) => {
                dispatchToLivewire('echo:fleet-updates,.trip.updated', e);
            });

    });
</script>

</html>