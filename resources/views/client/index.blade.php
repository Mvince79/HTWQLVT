@extends('client.layouts.main')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="mb-4">
        <h3 class="fw-bold">Tài xế</h3>
        <p class="text-muted">Chuyến xe của bạn</p>
    </div>

    <!-- THỐNG KÊ -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6 class="text-muted">Tổng chuyến</h6>
                    <h3 class="fw-bold">12</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6 class="text-muted">Đang chạy</h6>
                    <h3 class="fw-bold text-success">3</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6 class="text-muted">Chờ</h6>
                    <h3 class="fw-bold text-warning">5</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6 class="text-muted">Hoàn thành</h6>
                    <h3 class="fw-bold text-primary">4</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- DANH SÁCH CHUYẾN -->
    <div class="row">

        <!-- CARD -->
        <div class="col-md-4 mb-4">
            <div class="card trip-card border-0 shadow-sm">

                <img src="https://via.placeholder.com/400x200" class="card-img-top">

                <div class="card-body">

                    <h5 class="fw-bold">Cần Thơ → TP.HCM</h5>

                    <p class="text-muted mb-2">
                        🕒 08:00 <br>
                        🚛 Xe: 51A-12345
                    </p>

                    <!-- Trạng thái -->
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select status">
                        <option>Đang chạy</option>
                        <option>Hoàn thành</option>
                    </select>

                    <!-- Nhiên liệu -->
                    <label class="form-label mt-2">Nhiên liệu (L)</label>
                    <input type="number" class="form-control fuel" placeholder="Nhập số lít">

                </div>

                <!-- Footer -->
                <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                    
                    <span class="badge bg-success">Đang thực hiện</span>

                    <button class="btn btn-success btn-sm save-btn">
                         Lưu
                    </button>

                </div>

            </div>
        </div>
 <div class="col-md-4 mb-4">
            <div class="card trip-card border-0 shadow-sm">

                <img src="https://via.placeholder.com/400x200" class="card-img-top">

                <div class="card-body">

                    <h5 class="fw-bold">Cần Thơ → TP.HCM</h5>

                    <p class="text-muted mb-2">
                        🕒 08:00 <br>
                        🚛 Xe: 51A-12345
                    </p>

                    <!-- Trạng thái -->
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select status">
                        <option>Đang chạy</option>
                        <option>Hoàn thành</option>
                    </select>

                    <!-- Nhiên liệu -->
                    <label class="form-label mt-2">Nhiên liệu (L)</label>
                    <input type="number" class="form-control fuel" placeholder="Nhập số lít">

                </div>

                <!-- Footer -->
                <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                    
                    <span class="badge bg-success">Đang thực hiện</span>

                    <button class="btn btn-success btn-sm save-btn">
                         Lưu
                    </button>

                </div>

            </div>
        </div>
         <div class="col-md-4 mb-4">
            <div class="card trip-card border-0 shadow-sm">

                <img src="https://via.placeholder.com/400x200" class="card-img-top">

                <div class="card-body">

                    <h5 class="fw-bold">Cần Thơ → TP.HCM</h5>

                    <p class="text-muted mb-2">
                        🕒 08:00 <br>
                        🚛 Xe: 51A-12345
                    </p>

                    <!-- Trạng thái -->
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select status">
                        <option>Đang chạy</option>
                        <option>Hoàn thành</option>
                    </select>

                    <!-- Nhiên liệu -->
                    <label class="form-label mt-2">Nhiên liệu (L)</label>
                    <input type="number" class="form-control fuel" placeholder="Nhập số lít">

                </div>

                <!-- Footer -->
                <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                    
                    <span class="badge bg-success">Đang thực hiện</span>

                    <button class="btn btn-success btn-sm save-btn">
                         Lưu
                    </button>

                </div>

            </div>
        </div>
    </div>

</div>

@endsection