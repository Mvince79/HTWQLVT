@extends('client.layouts.main')
@section('content')




<div class="container mt-4">

    <!-- FILTER -->
    <div class="mb-3">
        <button class="btn btn-secondary btn-sm">Tất cả</button>
        <button class="btn btn-warning btn-sm">Đang giao</button>
        <button class="btn btn-success btn-sm">Hoàn thành</button>
    </div>

    <!-- CARD 1 -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">ORD12345</h5>
                    <p class="mb-1">🚛 Thép cuộn</p>
                    <p class="mb-1">⚖️ 8500 kg</p>
                </div>

                <span class="badge bg-warning text-dark">Đang giao</span>
            </div>

            <hr>

            <p><strong>📍 Lấy hàng:</strong> Kho A, Quận 1</p>
            <p><strong>📦 Giao hàng:</strong> KCN Sóng Thần</p>

            <div class="d-flex flex-wrap gap-2">
                <select class="form-select form-select-sm w-auto">
                    <option>Chưa bắt đầu</option>
                    <option selected>Đang giao</option>
                    <option>Hoàn thành</option>
                    <option>Hủy</option>
                </select>

                <button class="btn btn-primary btn-sm">Cập nhật</button>

                <input type="number" class="form-control form-control-sm w-auto" placeholder="Lít xăng">
                <button class="btn btn-success btn-sm">Lưu xăng</button>
            </div>

        </div>
    </div>

    <!-- CARD 2 -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">ORD12346</h5>
                    <p class="mb-1">🚛 Máy lạnh</p>
                    <p class="mb-1">⚖️ 2000 kg</p>
                </div>

                <span class="badge bg-secondary">Chưa bắt đầu</span>
            </div>

            <hr>

            <p><strong>📍 Lấy hàng:</strong> Nhà Bè</p>
            <p><strong>📦 Giao hàng:</strong> Quận 9</p>

            <div class="d-flex flex-wrap gap-2">
                <select class="form-select form-select-sm w-auto">
                    <option selected>Chưa bắt đầu</option>
                    <option>Đang giao</option>
                    <option>Hoàn thành</option>
                    <option>Hủy</option>
                </select>

                <button class="btn btn-primary btn-sm">Cập nhật</button>

                <input type="number" class="form-control form-control-sm w-auto" placeholder="Lít xăng">
                <button class="btn btn-success btn-sm">Lưu xăng</button>
            </div>

        </div>
    </div>

    <!-- CARD 3 -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">ORD12347</h5>
                    <p class="mb-1">🚛 Gạch xây</p>
                    <p class="mb-1">⚖️ 10000 kg</p>
                </div>

                <span class="badge bg-success">Hoàn thành</span>
            </div>

            <hr>

            <p><strong>📍 Lấy hàng:</strong> Hóc Môn</p>
            <p><strong>📦 Giao hàng:</strong> Đồng Nai</p>

            <div class="d-flex flex-wrap gap-2">
                <select class="form-select form-select-sm w-auto">
                    <option>Chưa bắt đầu</option>
                    <option>Đang giao</option>
                    <option selected>Hoàn thành</option>
                    <option>Hủy</option>
                </select>

                <button class="btn btn-primary btn-sm">Cập nhật</button>

                <input type="number" class="form-control form-control-sm w-auto" placeholder="Lít xăng">
                <button class="btn btn-success btn-sm">Lưu xăng</button>
            </div>

        </div>
    </div>

</div>




@endsection