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
    Echo.channel('admin-notifications')
        .listen('.incident.new', (e) => {
            console.log('Sự cố mới:', e);
        });

    Echo.channel('orders')
        .listen('.order.updated', (e) => {
            if (window.Filament) {
                new FilamentNotification()
                    .title('Cập nhật đơn hàng')
                    .body(e.message)
                    .success()
                    .send();
            } else {
                alert(e.message);
            }
        });
</script>

</html>