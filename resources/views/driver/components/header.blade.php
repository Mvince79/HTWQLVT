<nav class="navbar navbar-dark bg-dark px-3">

    <!-- Toggle sidebar -->
    <button class="btn btn-outline-light" id="menu-toggle">☰</button>

    <span class="navbar-brand ms-3">Tài xế</span>

    <div class="ms-auto">
        <span class="text-white me-3">Xin chào, Tài xế</span>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <a href="#" class="btn btn-outline-light btn-sm"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Đăng xuất
        </a>
    </div>

</nav>
