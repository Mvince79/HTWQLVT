@vite(['resources/js/app.js'])

<script type="module">
    // Chờ Livewire và Echo sẵn sàng
    document.addEventListener('livewire:initialized', () => {

        Echo.channel('fleet-updates')
            .listen('.trip.updated', (e) => {
                const statusMap = {
                    not_started: 'Chưa bắt đầu',
                    in_progress: 'Đang chạy',
                    completed: 'Hoàn thành',
                    cancelled: 'Đã huỷ',
                };
                new FilamentNotification()
                    .title(`Chuyến #${e.trip_id} cập nhật`)
                    .body(`Tài xế: ${e.driver_name} | Xe: ${e.plate_number} | Trạng thái: ${statusMap[e.status] ?? e.status}`)
                    .info()
                    .send();

                // Dispatch tới tất cả Livewire components trên trang (Filament List pages)
                Livewire.dispatch('echo:fleet-updates,.trip.updated', e);
            })
            .listen('.fuel.logged', (e) => {
                new FilamentNotification()
                    .title('Nhiên liệu ghi nhận')
                    .body(`Tài xế: ${e.driver} | Xe: ${e.vehicle} | ${e.fuel_amount}L`)
                    .success()
                    .send();

                Livewire.dispatch('echo:fleet-updates,.fuel.logged', e);
            });

        Echo.channel('admin-notifications')
            .listen('.incident.reported', (e) => {
                new FilamentNotification()
                    .title(`🚨 Sự cố: ${e.type}`)
                    .body(`Tài xế: ${e.driver_name} | ${e.note}`)
                    .danger()
                    .persistent()
                    .send();

                Livewire.dispatch('echo:admin-notifications,.incident.reported', e);
            });



        Echo.channel('orders')
            .listen('.order.updated', (e) => {
                new FilamentNotification()
                    .title(`Đơn hàng ${e.order_code} cập nhật`)
                    .body(e.message)
                    .info()
                    .send();

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