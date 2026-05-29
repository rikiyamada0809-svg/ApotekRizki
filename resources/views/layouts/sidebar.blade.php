<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="{{ route('dashboard') }}" class="app-brand-link">
              <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/logo-apotek.png') }}" alt="Apotek Rizki Logo" class="h-auto rounded-circle" style="max-height: 38px; max-width: 38px; object-fit: cover;" />
              </span>
              <span class="app-brand-text demo menu-text fw-bold">Apotek Rizki</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
              <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Home -->
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-layout-dashboard"></i>
                <div>Dashboard</div>
              </a>
            </li>

            <!-- Kasir -->
            <li class="menu-item {{ request()->routeIs('kasir.*') ? 'active' : '' }}">
              <a href="{{ route('kasir.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
                <div>Kasir</div>
              </a>
            </li>

            {{-- Kategori --}}
            <li class="menu-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
              <a href="{{ route('categories.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-category"></i>
                <div>Kategori</div>
              </a>
            </li>

            {{-- Inventari --}}
            <li class="menu-item {{ request()->routeIs('obats.*') ? 'active' : '' }}">
              <a href="{{ route('obats.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-box"></i>
                <div>Inventari</div>
              </a>
            </li>

            <!-- Logout -->
            <li class="menu-item">
              <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-sidebar-form').submit();" class="menu-link">
                <i class="menu-icon tf-icons ti ti-logout"></i>
                <div data-i18n="Logout">Logout</div>
              </a>
              <form id="logout-sidebar-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </li>
          </ul>
        </aside>
