@vite(['resources/js/app.js'])

<script type="module">
Echo.channel('fleet-updates')
    .listen('.trip.updated', (e) => {
        const statusMap = {
            not_started : 'Chưa bắt đầu',
            in_progress : 'Đang chạy',
            completed   : 'Hoàn thành',
            cancelled   : 'Đã huỷ',
        };
        new FilamentNotification()
            .title(`Chuyến #${e.trip_id} cập nhật`)
            .body(`Tài xế: ${e.driver_name} | Xe: ${e.plate_number} | Trạng thái: ${statusMap[e.status] ?? e.status}`)
            .info()
            .send();
    })
    .listen('.fuel.logged', (e) => {
        new FilamentNotification()
            .title('Nhiên liệu ghi nhận')
            .body(`Tài xế: ${e.driver} | Xe: ${e.vehicle} | ${e.fuel_amount}L`)
            .success()
            .send();
    });

Echo.channel('admin-notifications')
    .listen('.incident.reported', (e) => {
        new FilamentNotification()
            .title(`Sự cố: ${e.type}`)
            .body(`Tài xế: ${e.driver_name} | ${e.note}`)
            .danger()
            .persistent()
            .send();
    });

Echo.channel('orders')
    .listen('.order.updated', (e) => {
        new FilamentNotification()
            .title('Đơn hàng cập nhật').body(e.message).info().send();
    });

Echo.channel('vehicle-updates')
    .listen('.vehicle.status.updated', (e) => {
        new FilamentNotification()
            .title(`Xe ${e.license_plate}`).warning().send();
    });
</script>