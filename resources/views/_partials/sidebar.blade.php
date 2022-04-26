@php
$optionsAuth['roles'] = [
['text' => 'Owner', 'value' => 'owner' ],
['text' => 'Admin', 'value' => 'admin'],
['text' => 'Kepala Cabang', 'value' => 'branch_head'],
['text' => 'Akutansi', 'value' => 'accountant'],
['text' => 'Kasir', 'value' => 'cashier'],
['text' => 'Material', 'value' => 'material'],
['text' => 'Purchaser' , 'value' => 'purchaser']
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
      {{-- <li class="nav-header">LAPORAN</li>
      <x-nav-item :icon="'fas fa-gas-pump'" :text="'Frekuensi Penggunaan Solar'" :href="'/transaksi/solar'" /> --}}
      @if (Auth::user()->role == 'admin' || Auth::user()->role == 'owner')
      <li class="nav-header">TRANSAKSI</li>
      <x-nav-item :icon="'fas fa-receipt'" :text="'Voucher'" :href="route('voucher.index')" />
      <x-nav-item :icon="'fas fa-shopping-cart'" :text="'Order'" :href="route('order.index')" />
      <x-nav-item :icon="'fas fa-gas-pump'" :text="'Solar'" :href="'/transaksi/solar'" />
      <x-nav-item :icon="'fas fa-money-bill'" :text="'Mutasi Hutang'" href="{{ route('debt-mutation.index') }}" />
      <x-nav-item :icon="'fas fa-money-bill'" :text="'Saldo Hutang'" href="{{ route('debt-mutation.balance') }}" />
      <x-nav-item :icon="'fas fa-truck-loading'" :text="'Mutasi Hutang Ritase'"
        href="{{ route('rit-mutation.index') }}" />
      <x-nav-item :icon="'fas fa-truck-loading'" :text="'Saldo Hutang Ritase'"
        href="{{ route('rit-mutation.balance') }}" />
      <x-nav-item :icon="'fas fa-cubes'" :text="'Mutasi Material'" href="{{ route('material-mutation.index') }}" />
      <x-nav-item :icon="'fas fa-cubes'" :text="'Saldo Material'" href="{{ route('material-mutation.balance') }}" />

      <li class="nav-header">AKUNTANSI</li>
      <x-nav-item :icon="'fas fa-book'" :text="'Entri Jurnal'" href="{{ route('journal.index') }}" />
      <x-nav-item :icon="'fas fa-book'" :text="'Neraca'" href="{{ route('balance.index') }}" />
      <x-nav-item :icon="'fas fa-book'" :text="'Laba Rugi'" href="{{ route('income.statement.index') }}" />
      <x-nav-item :icon="'fas fa-book'" :text="'Buku Besar'" href="{{ route('general.ledger.index') }}" />

      <li class="nav-header">TRANSAKSI PEMBELIAN</li>
      <x-nav-item :icon="'fas fa-credit-card'" :text="'Purchase'" :href="route('purchasing.index')" />

      <li class="nav-header">ANGGARAN</li>
      <x-nav-item :icon="'fas fa-calculator'" :text="'Anggaran'" :href="route('budget.index')" />

      {{-- <li class="nav-header">LAPORAN</li>
      <x-nav-item :icon="'fas fa-gas-pump'" :text="'Solar'" :href="route('purchasing.index')" /> --}}

      <li class="nav-header">MASTER DATA</li>
      <x-nav-item :icon="'fas fa-list'" :text="'List Kategori'" href="{{ route('category.list') }}" />
      <x-nav-item :icon="'fas fa-sitemap'" :text="'Cabang'" href="{{ route('branch.index') }}" />
      <x-nav-item :icon="'fas fa-users'" :text="'Pengguna'" href="{{ route('user.index') }}" />
      <x-nav-item :icon="'fas fa-project-diagram'" :text="'Proyek'" href="{{ route('project.index') }}" />
      <x-nav-item :icon="'fas fa-truck-moving'" :text="'Kendaraan'" href="{{ route('vehicle.index') }}" />
      <x-nav-item :icon="'fas fa-address-book'" :text="'Pengendara'" href="{{ route('driver.index') }}" />
      <x-nav-item :icon="'fas fa-industry'" :text="'Vendor'" href="{{ route('vendor.index') }}" />
      <x-nav-item :icon="'fas fa-cubes'" :text="'Material'" href="{{ route('material.index') }}" />

      <x-nav-item :icon="'fas fa-list'" :text="'Kelompok Mata Anggaran'" href="{{ route('big.index') }}" />
      <x-nav-item :icon="'fas fa-list'" :text="'Mata Anggaran'" href="{{ route('bi.index') }}" />
      <x-nav-item :icon="'fas fa-list'" :text="'Sub-Mata Anggaran'" href="{{ route('sbi.index') }}" />
      @endif

      @if (Auth::user()->role == 'purchaser' )
      <li class="nav-header">TRANSAKSI PEMBELIAN</li>
      <x-nav-item :icon="'fas fa-credit-card'" :text="'Purchasing'" :href="route('purchasing.index')" />
      @endif

      @if (Auth::user()->role == 'branch_head')
      <li class="nav-header">TRANSAKSI</li>
      <x-nav-item :icon="'fas fa-receipt'" :text="'Voucher'" :href="route('voucher.index')" />
      <x-nav-item :icon="'fas fa-shopping-cart'" :text="'Order'" :href="route('order.index')" />
      <x-nav-item :icon="'fas fa-gas-pump'" :text="'Solar'" :href="'/transaksi/solar'" />
      <x-nav-item :icon="'fas fa-money-bill'" :text="'Mutasi Hutang'" href="{{ route('debt-mutation.index') }}" />
      <x-nav-item :icon="'fas fa-money-bill'" :text="'Saldo Hutang'" href="{{ route('debt-mutation.balance') }}" />
      <x-nav-item :icon="'fas fa-truck-loading'" :text="'Mutasi Hutang Ritase'"
        href="{{ route('rit-mutation.index') }}" />
      <x-nav-item :icon="'fas fa-truck-loading'" :text="'Saldo Hutang Ritase'"
        href="{{ route('rit-mutation.balance') }}" />
      <x-nav-item :icon="'fas fa-cubes'" :text="'Mutasi Material'" href="{{ route('material-mutation.index') }}" />
      <x-nav-item :icon="'fas fa-cubes'" :text="'Saldo Material'" href="{{ route('material-mutation.balance') }}" />

      <li class="nav-header">MASTER DATA</li>
      <x-nav-item :icon="'fas fa-project-diagram'" :text="'Proyek'" href="{{ route('project.index') }}" />
      <x-nav-item :icon="'fas fa-industry'" :text="'Vendor'" href="{{ route('vendor.index') }}" />
      <x-nav-item :icon="'fas fa-cubes'" :text="'Material'" href="{{ route('material.index') }}" />
      @endif

      @if (Auth::user()->role == 'material')
      <li class="nav-header">TRANSAKSI</li>
      <x-nav-item :icon="'fas fa-gas-pump'" :text="'Solar'" :href="'/transaksi/solar'" />
      <x-nav-item :icon="'fas fa-truck-loading'" :text="'Mutasi Hutang Ritase'"
        href="{{ route('rit-mutation.index') }}" />
      <x-nav-item :icon="'fas fa-truck-loading'" :text="'Saldo Hutang Ritase'"
        href="{{ route('rit-mutation.balance') }}" />
      <x-nav-item :icon="'fas fa-cubes'" :text="'Mutasi Material'" href="{{ route('material-mutation.index') }}" />
      <x-nav-item :icon="'fas fa-cubes'" :text="'Saldo Material'" href="{{ route('material-mutation.balance') }}" />

      <li class="nav-header">MASTER DATA</li>
      <x-nav-item :icon="'fas fa-truck-moving'" :text="'Kendaraan'" href="{{ route('vehicle.index') }}" />
      <x-nav-item :icon="'fas fa-cubes'" :text="'Material'" href="{{ route('material.index') }}" />
      @endif

      @if (Auth::user()->role == 'cashier')
      <li class="nav-header">TRANSAKSI</li>
      <x-nav-item :icon="'fas fa-receipt'" :text="'Voucher'" :href="route('voucher.index')" />
      <x-nav-item :icon="'fas fa-shopping-cart'" :text="'Order'" :href="route('order.index')" />
      <x-nav-item :icon="'fas fa-money-bill'" :text="'Mutasi Hutang'" href="{{ route('debt-mutation.index') }}" />
      <x-nav-item :icon="'fas fa-money-bill'" :text="'Saldo Hutang'" href="{{ route('debt-mutation.balance') }}" />

      <li class="nav-header">MASTER DATA</li>
      <x-nav-item :icon="'fas fa-industry'" :text="'Vendor'" href="{{ route('vendor.index') }}" />
      @endif

      @if (Auth::user()->role == 'accountant')
      <li class="nav-header">TRANSAKSI</li>
      <x-nav-item :icon="'fas fa-gas-pump'" :text="'Solar'" :href="'/transaksi/solar'" />
      <x-nav-item :icon="'fas fa-money-bill'" :text="'Mutasi Hutang'" href="{{ route('debt-mutation.index') }}" />
      <x-nav-item :icon="'fas fa-money-bill'" :text="'Saldo Hutang'" href="{{ route('debt-mutation.balance') }}" />
      <x-nav-item :icon="'fas fa-cubes'" :text="'Saldo Material'" href="{{ route('material-mutation.balance') }}" />

      <li class="nav-header">AKUNTANSI</li>

      <x-nav-item :icon="'fas fa-book'" :text="'Entri Jurnal'" href="{{ route('journal.index') }}" />
      <x-nav-item :icon="'fas fa-book'" :text="'Neraca'" href="{{ route('balance.index') }}" />
      <x-nav-item :icon="'fas fa-book'" :text="'Laba Rugi'" href="{{ route('income.statement.index') }}" />
      <x-nav-item :icon="'fas fa-book'" :text="'Buku Besar'" href="{{ route('general.ledger.index') }}" />
      <li class="nav-header">MASTER DATA</li>
      <x-nav-item :icon="'fas fa-project-diagram'" :text="'Proyek'" href="{{ route('project.index') }}" />
      @endif

    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
<i class="fas fa-truck-loading"></i>
<!-- Sidebar Menu -->
