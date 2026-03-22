@extends('client.layouts.main')

@section('content')

    <div class="container-fluid">

        <!-- HEADER -->
        <div class="mb-4">
            <h3 class="fw-bold">
                Tài xế: {{ $driver->user->name ?? 'N/A' }}
            </h3>

            <p class="text-muted">
                SĐT: {{ $driver->phone ?? 'N/A' }} |
                GPLX: {{ $driver->license_number ?? 'N/A' }} |
                Trạng thái: {{ $driver->working_status ?? 'N/A' }}
            </p>
        </div>

        <!-- THỐNG KÊ -->
        <div class="row mb-4">

            <div class="col-md-3">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Tổng chuyến</h6>
                        <h3 class="fw-bold">{{ count($trips) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Đang chạy</h6>
                        <h3 class="fw-bold text-success">
                            {{ collect($trips)->where('status', 'in_progress')->count() }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Chưa bắt đầu</h6>
                        <h3 class="fw-bold text-warning">
                            {{ collect($trips)->where('status', 'not_started')->count() }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Hoàn thành</h6>
                        <h3 class="fw-bold text-primary">
                            {{ collect($trips)->where('status', 'completed')->count() }}
                        </h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- DANH SÁCH CHUYẾN -->
        <div class="row">

            @foreach ($trips as $trip)

                <div class="col-md-4 mb-4">
                    <div class="card trip-card border-0 shadow-sm">

                        <img src="https://via.placeholder.com/400x200" class="card-img-top">

                        <div class="card-body">

                            <h5 class="fw-bold">
                                {{ $trip->order->from ?? 'N/A' }} → {{ $trip->order->to ?? 'N/A' }}
                            </h5>

                            <p class="text-muted mb-2">
                                🕒 {{ $trip->start_time ?? 'Chưa có' }} <br>
                                🚛 Xe: {{ $trip->vehicle->plate_number ?? 'N/A' }}
                            </p>

                            <!-- Trạng thái -->
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select">
                                <option value="in_progress" {{ $trip->status == 'in_progress' ? 'selected' : '' }}>
                                    Đang chạy
                                </option>

                                <option value="completed" {{ $trip->status == 'completed' ? 'selected' : '' }}>
                                    Hoàn thành
                                </option>
                            </select>

                            <!-- Nhiên liệu -->
                            <label class="form-label mt-2">Nhiên liệu (L)</label>
                            <input type="number" class="form-control" value="{{ $trip->fuel ?? '' }}">

                        </div>

                        <div class="card-footer bg-white border-0 d-flex justify-content-between">
                            <span class="badge 
                                    {{ $trip->status == 'completed' ? 'bg-primary' : 'bg-success' }}">
                                {{ $trip->status }}
                            </span>

                            <button class="btn btn-success btn-sm">
                                Lưu
                            </button>
                        </div>

                    </div>
                </div>

            @endforeach

        </div>

    </div>

@endsection