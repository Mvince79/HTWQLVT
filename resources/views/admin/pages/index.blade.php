@extends('admin.layouts.main')

@section('content')

    <h4 class="mb-4 fw-bold">Dashboard</h4>

    <div class="row g-3">

        @foreach ([
                ['Reports', '59'],
                ['Revenue', '$96k'],
                ['Trips', '696'],
                ['Vehicles', '356'],
                ['Clients', '3650'],
                ['Drivers', '96'],
            ] as $item)

            <div class="col-md-2">
                <div class="card p-3 text-center shadow-sm">
                    <small class="text-muted">{{ $item[0] }}</small>
                    <h5 class="fw-bold text-primary">{{ $item[1] }}</h5>
                </div>
            </div>

        @endforeach

    </div>

    <div class="row mt-4">

        <div class="col-md-8">
            <div class="card p-4 shadow-sm">
                <h6 class="fw-semibold">Revenue Overview</h6>
                <p class="text-muted small">Monthly performance</p>

                <div class="bg-light rounded" style="height:200px;"></div>

                <div class="mt-3 fw-bold text-primary">
                    $63,489.50
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card p-4 mb-3 shadow-sm">
                <h6 class="text-muted">Yearly</h6>
                <h4 class="text-primary">$36,358</h4>
            </div>

            <div class="card p-4 shadow-sm">
                <h6 class="text-muted">Monthly</h6>
                <h4 class="text-success">$6,820</h4>
            </div>

        </div>

    </div>

@endsection