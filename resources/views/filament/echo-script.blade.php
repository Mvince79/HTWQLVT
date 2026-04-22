@vite(['resources/js/app.js'])

<script type="module">
    // Chờ Livewire và Echo sẵn sàng
    document.addEventListener('livewire:initialized', () => {

        const recentMessages = new Set();
        function notify(title, body, type = 'info') {
            const key = `${title}|${body}`;
            if (recentMessages.has(key)) return;
            recentMessages.add(key);
            setTimeout(() => recentMessages.delete(key), 3000);

            new FilamentNotification()
                .title(title)
                .body(body)
            [type]()
                .send();
        }

        const statusMap = {
            not_started: 'Chờ khởi hành',
            in_progress: 'Đang chạy',
            completed: 'Hoàn thành',
            cancelled: 'Đã huỷ',
            pending: 'Chờ xử lý',
            delivering: 'Đang giao',
        };

        Echo.channel('fleet-updates')
            .listen('.trip.updated', (e) => {
                notify(
                    `Chuyến #${e.trip_id}`,
                    `${e.driver_name} · ${e.plate_number} · ${statusMap[e.status] ?? e.status}`,
                    e.status === 'cancelled' ? 'danger' : 'info'
                );
                Livewire.dispatch('echo:fleet-updates,.trip.updated', e);
            })
            .listen('.fuel.logged', (e) => {
                notify('Nhiên liệu ghi nhận', `${e.driver} · ${e.vehicle} · ${e.fuel_amount}L`, 'success');
                Livewire.dispatch('echo:fleet-updates,.fuel.logged', e);
            });

        Echo.channel('admin-notifications')
            .listen('.incident.reported', (e) => {
                new FilamentNotification()
                    .title(`Sự cố: ${e.type}`)
                    .body(`${e.driver_name} · ${e.note}`)
                    .danger()
                    .persistent()
                    .send();
                Livewire.dispatch('echo:admin-notifications,.incident.reported', e);
            });


        Echo.channel('orders')
            .listen('.order.updated', (e) => {
                notify(`Đơn ${e.order_code}`, e.message, 'info');
                Livewire.dispatch('echo:orders,.order.updated', e);
            });

        Echo.channel('vehicle-updates')
            .listen('.vehicle.status.updated', (e) => {
                const vehicleStatusMap = {
                    active: 'Đang hoạt động',
                    inactive: 'Không hoạt động',
                    maintenance: 'Bảo trì'
                };
                new FilamentNotification()
                    .title(`Xe ${e.license_plate} cập nhật`)
                    .body(`Trạng thái: ${vehicleStatusMap[e.status] ?? e.status}`)
                    .warning()
                    .send();
            });

    });
</script>