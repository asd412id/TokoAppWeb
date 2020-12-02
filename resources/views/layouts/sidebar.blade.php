<aside class="main-sidebar sidebar-dark-info elevation-4">
  <a href="{{ asset('assets') }}/index3.html" class="brand-link navbar-info">
    <img src="{{ asset('assets') }}/theme/img/app_icon.png"
    alt="{{ getenv('APP_NAME') }} Logo"
    class="brand-image img-circle elevation-3"
    style="opacity: .8">
    <span class="brand-text font-weight-light">Halaman {{ ucwords($user->role) }}</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('assets') }}/theme/img/avatar.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ $user->name }}</a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @if (auth()->user()->isAdmin)
          <li class="nav-item has-treeview">
            <a href="{{ route('index') }}" class="nav-link {{ (\Request::url() == route('index')?'active':'') }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Beranda
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="{{ route('kategori.index') }}" class="nav-link {{ (strpos(\Request::url(),route('kategori.index'))!==false?'active':'') }}">
              <i class="nav-icon fas fa-tags"></i>
              <p>
                Kategori Barang
              </p>
            </a>
          </li>
        @endif
        @if (auth()->user()->isAdmin || auth()->user()->isStoker)
          <li class="nav-item has-treeview">
            <a href="{{ route('barang.index') }}" class="nav-link {{ (strpos(\Request::url(),route('barang.index'))!==false?'active':'') }}">
              <i class="nav-icon fas fa-boxes"></i>
              <p>
                Daftar Barang
              </p>
            </a>
          </li>
        @endif
        @if (auth()->user()->isAdmin || auth()->user()->isCashier)
          <li class="nav-item has-treeview">
            <a href="{{ route('transaksi.index') }}" class="nav-link {{ (strpos(\Request::url(),route('transaksi.index'))!==false?'active':'') }}">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Transaksi
              </p>
            </a>
          </li>
        @endif
        @if (auth()->user()->isAdmin)
          <li class="nav-item has-treeview">
            <a href="{{ route('rekapitulasi.index') }}" class="nav-link {{ (strpos(\Request::url(),route('rekapitulasi.index'))!==false?'active':'') }}">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Rekapitulasi
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="{{ route('user.index') }}" class="nav-link {{ (strpos(\Request::url(),route('user.index'))!==false?'active':'') }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Daftar User
              </p>
            </a>
          </li>
        @endif
      </ul>
    </nav>
  </div>
</aside>
