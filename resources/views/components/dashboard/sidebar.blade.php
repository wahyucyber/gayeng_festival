<!-- LOGO -->
<div class="navbar-brand-box">
    <!-- Dark Logo-->
    <a href="{{ route("admin.dashboard") }}" class="logo logo-dark">
        <span class="logo-sm">
            <img src="{{ env("APP_URL") }}/assets/images/logo-sm.png" alt="" height="22">
        </span>
        <span class="logo-lg">
            <img src="{{ env("APP_URL") }}/assets/images/logo-dark.png" alt="" height="17">
        </span>
    </a>
    <!-- Light Logo-->
    <a href="{{ route("admin.dashboard") }}" class="logo logo-light">
        <span class="logo-sm">
            <img src="{{ env("APP_URL") }}/assets/images/logo-sm.png" alt="" height="22">
        </span>
        <span class="logo-lg">
            <img src="{{ env("APP_URL") }}/assets/images/logo-light.png" alt="" height="17">
        </span>
    </a>
    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
        id="vertical-hover">
        <i class="ri-record-circle-line"></i>
    </button>
</div>

<div id="scrollbar">
    <div class="container-fluid">

        <div id="two-column-menu">
        </div>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="menu-title"><span data-key="t-menu">Master Data</span></li>
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs("admin.dashboard") ? "active" : "" }}" href="{{ route("admin.dashboard") }}"><i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs("admin.event*") ? "active" : "" }}" href="{{ route("admin.event") }}"><i class="ri-calendar-event-fill"></i> <span data-key="t-events">Acara</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs("admin.news*") ? "active" : "" }}" href="{{ route("admin.news") }}"><i class="ri-newspaper-line"></i> <span data-key="t-news">Berita</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link {{ request()->routeIs("admin.order*") ? "active" : "" }}" href="{{ route("admin.order") }}"><i class="ri-exchange-line"></i> <span data-key="t-orders">Transaksi</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Sidebar -->
</div>
