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
    const driverId = Number("{{ auth()->user()?->driver?->id ?? 0 }}");

    function notify(message, type = 'success') {
        window.dispatchEvent(
            new CustomEvent('notify', { detail: { message, type } })
        );
    }

    function refreshDashboard() {
        if (window.Livewire) {
            window.Livewire.dispatch('$refresh');
        }
    }

    if (driverId > 0) {
        Echo.channel(`driver.${driverId}`)
            .listen('.trip.assigned', (e) => {
                notify(`Chuyến mới #${e.trip_id}: ${e.pickup_location} → ${e.delivery_location}`, 'success');
                refreshDashboard();
            });
    }

    Echo.channel('orders')
        .listen('.order.updated', (e) => {
            notify(`${e.message}`, 'info');
            refreshDashboard();
        });

    Echo.channel('vehicle-updates')
        .listen('.vehicle.status.updated', (e) => {
            notify(`Xe ${e.license_plate} → ${e.status}`, 'info');
            refreshDashboard();
        });
</script>
</html>