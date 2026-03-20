<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563EB;
            --primary-hover: #1D4ED8;
        }

        body {
            background-color: #F5F7FB;
        }

        #main-wrapper {
            display: flex;
        }

        .page-wrapper {
            flex: 1;
            margin-left: 260px;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        /* override bootstrap primary */
        .btn-primary,
        .bg-primary {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover) !important;
        }

        .nav-link:hover {
            background-color: #f1f5f9;
            transition: 0.2s;
        }
    </style>
</head>

<body>

    <div id="main-wrapper">

        @include('admin.components.sidebar')

        <div class="page-wrapper">
            @include('admin.components.header')

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>

    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</html>