    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
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
            <x-nav-item :icon="'fas fa-gas-pump'" :text="'Solar'" :href="'/transaksi/solar'"/>
            <x-nav-item :icon="'fas fa-money-bill'" :text="'Mutasi Hutang'" :href="'/transaksi/mutasi-hutang'"/>
          <li class="nav-header">MASTER DATA</li>
            <x-nav-item :icon="'fas fa-sitemap'" :text="'Cabang'" :href="'/master-data/cabang'"/>
            <x-nav-item :icon="'fas fa-users'" :text="'Pengguna'" :href="'/master-data/pengguna'"/>
            <x-nav-item :icon="'fas fa-project-diagram'" :text="'Proyek'" :href="'/master-data/proyek'"/>
            <x-nav-item :icon="'fas fa-truck-moving'" :text="'Kendaraan'" :href="'/master-data/kendaraan'"/>
            <x-nav-item :icon="'fas fa-address-book'" :text="'Pengendara'" :href="'/master-data/pengendara'"/>
            <x-nav-item :icon="'fas fa-industry'" :text="'Vendor'" :href="'/master-data/vendor'"/>
            <x-nav-item :icon="'fas fa-cubes'" :text="'Material'" :href="'/master-data/material'"/>
            <x-nav-item :icon="'fas fa-balance-scale'" :text="'Jenis Mutasi Hutang'" :href="'/master-data/jenis-mutasi-hutang'"/>
            <x-nav-item :icon="'fas fa-book'" :text="'Saldo Normal XXX'" :href="'/master-data/saldo-normal'"/>
            <x-nav-item :icon="'fas fa-book'" :text="'Jenis Jurnal XXX'" :href="'/master-data/jenis-jurnal'"/>
            <x-nav-item :icon="'fas fa-balance-scale'" :text="'Jenis Laporan XXX'" :href="'/master-data/jenis-laporan'"/>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->