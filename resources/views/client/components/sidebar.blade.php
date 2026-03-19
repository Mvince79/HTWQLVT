<aside class="bg-white border-end position-fixed vh-100 p-3" style="width:260px;">

    <h5 class="fw-bold mb-4 text-primary">LOGISTICS</h5>

    <ul class="nav flex-column gap-1">

        <li class="nav-item">
            <a class="nav-link rounded px-3 py-2
                {{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-white' : 'text-dark' }}"
                href="">
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link rounded px-3 py-2
                {{ request()->routeIs('admin.drivers.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="#">
                Tài xế
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link rounded px-3 py-2
                {{ request()->routeIs('admin.vehicles.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="#">
                Phương tiện
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link rounded px-3 py-2
                {{ request()->routeIs('admin.orders.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="#">
                Đơn hàng
            </a>
        </li>

    </ul>

</aside>