<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
.trip-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.trip-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.card-body h5 {
    font-size: 18px;
}

.badge {
    font-size: 13px;
    padding: 6px 10px;
}
</style>
<body>
    <div id="main-wrapper">

        <div class="page-wrapper">
            @include('client.components.header')

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>

    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>


</html>