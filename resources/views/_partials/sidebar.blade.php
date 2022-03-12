@php
  $optionsAuth['roles'] = [
    ['text' => 'Owner', 'value' => 'owner' ],
    ['text' => 'Admin', 'value' => 'admin'],
    ['text' => 'Kepala Cabang', 'value' => 'branch_head'],
    ['text' => 'Akutansi', 'value' => 'accountant'],
    ['text' => 'Kasir', 'value' => 'cashier'],
    ['text' => 'Material', 'value' => 'material'],
  ];
@endphp

<!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->username }}</a>
          <a href="#" class="d-block">
            @foreach ($optionsAuth['roles'] as $role)
              @if($role['value'] == Auth::user()->role)
                {{ $role['text'] }}
              @endif
            @endforeach
          </a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">TRANSAKSI</li>
            <x-nav-item :icon="'fas fa-receipt'" :text="'Order'" :href="route('order.index')"/>
            <x-nav-item :icon="'fas fa-gas-pump'" :text="'Solar'" :href="'/transaksi/solar'"/>
            <x-nav-item :icon="'fas fa-money-bill'" :text="'Mutasi Hutang'" href="{{ route('debt-mutation.index') }}"/>
            <x-nav-item :icon="'fas fa-cubes'" :text="'Mutasi Material'" href="{{ route('material-mutation.index') }}"/>
            <x-nav-item :icon="'fas fa-money-bill'" :text="'Saldo Hutang'" href="{{ route('debt-mutation.balance') }}"/>

          @if (Auth::user()->role == 'owner' || Auth::user()->role == 'admin')
            <li class="nav-header">MASTER DATA</li>
              <x-nav-item :icon="'fas fa-sitemap'" :text="'Cabang'" href="{{ route('branch.index') }}"/>
              <x-nav-item :icon="'fas fa-users'" :text="'Pengguna'" href="{{ route('user.index') }}"/>
              <x-nav-item :icon="'fas fa-project-diagram'" :text="'Proyek'" href="{{ route('project.index') }}"/>
              <x-nav-item :icon="'fas fa-truck-moving'" :text="'Kendaraan'" href="{{ route('vehicle.index') }}"/>
              <x-nav-item :icon="'fas fa-address-book'" :text="'Pengendara'" href="{{ route('driver.index') }}"/>
              <x-nav-item :icon="'fas fa-industry'" :text="'Vendor'" href="{{ route('vendor.index') }}"/>
              <x-nav-item :icon="'fas fa-cubes'" :text="'Material'" href="{{ route('material.index') }}"/>
            </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->