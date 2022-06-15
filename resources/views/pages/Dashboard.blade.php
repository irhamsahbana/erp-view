@extends('App')

@php $breadcrumbList = [ [ 'name' => 'Home', 'href' => '/' ], [
'name' => 'Pengendara' ], ]; @endphp @section('content-header', 'Dashboard')
@section('breadcrumb')

@endsection @section('content')
<x-content>
    <div >

        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            @if (Auth::user()->role == 'admin' ||  Auth::user()->role == 'owner' || Auth::user()->role == 'cashier')
            <x-card-collapsible :title="'Kas Hari Ini'">

              <div class="row">
                  {{-- Kas Awal --}}
                <div class="col-lg-3">
                  <div class="small-box bg-primary">
                    <div class="inner">
                        <p>Saldo Kas Awal</p>
                      <h3 class="text-right"> {{number_format($set_balance)}} </h3>

                    </div>
                    <div class="icon">

                      <i class="ion ion-wallet"></i>
                    </div>

                  </div>
                </div>
                {{-- Kas Masuk --}}
                <div class="col-lg-3">
                  <div class="small-box bg-olive">
                    <div class="inner">
                        <p>Kas Masuk Hari Ini</p>
                      <h3 class="text-right"> {{number_format($cash_in)}} </h3>

                    </div>
                    <div class="icon">
                        <i class="ion ion-plus"></i>
                    </div>

                  </div>
                </div>
                {{-- Kas Keluar --}}
                <div class="col-lg-3">
                  <div class="small-box bg-warning">
                    <div class="inner">
                        <p>Kas Keluar Hari Ini</p>
                      <h3 class="text-right"> {{number_format($cash_out)}} </h3>

                    </div>
                    <div class="icon">
                      <i class="ion ion-minus"></i>
                    </div>

                  </div>
                </div>
                {{-- Kas Hari Ini --}}
                <div class="col-lg-3">
                  <div class="small-box bg-success">
                    <div class="inner">
                        <p>Total Saldo kas Hari ini</p>
                      <h3 class="text-right"> {{number_format($total_cash)}} </h3>

                    </div>
                    <div class="icon">
                      <i class="ion ion-money"></i>
                    </div>

                  </div>
                </div>
                <!-- ./col -->
              </div>
            </x-card-collapsible>
              @endif


          {{-- Penagihan --}}
          @if (Auth::user()->role == 'admin' ||  Auth::user()->role == 'owner' || Auth::user()->role == 'Account Receivable')
        <x-card-collapsible :title="'Piutang Usaha per hari ini'">
            <div class="row">
              <div class="col-lg-6">

                <div class="small-box bg-info">
                  <div class="inner">
                      <p>Piutang Belum Terbayarkan</p>
                    <h3 class="text-right"> {{number_format($receivable_total)}} </h3>

                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>


              <!-- ./col -->
              <div class="col-lg-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                  <div class="inner">

                      <p>Piutang Lewat Batas Waktu</p>
                      <h3 class="text-right"> {{number_format($receivable_duedate)}} </h3>

                  </div>
                  <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <!-- ./col -->
            </div>
        </x-card-collapsible>
            @endif
            {{-- Bill --}}
          @if (Auth::user()->role == 'admin' ||  Auth::user()->role == 'owner' || Auth::user()->role == 'purchaser')
        <x-card-collapsible :title="'Hutang Usaha per hari ini'">
            <div class="row">
              <div class="col-lg-6">

                <div class="small-box bg-info">
                  <div class="inner">
                      <p>Hutang Belum Terbayarkan</p>
                    <h3 class="text-right"> {{number_format($bill_total)}} </h3>

                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>


              <!-- ./col -->
              <div class="col-lg-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                  <div class="inner">

                      <p>Hutang Lewat Batas Waktu</p>
                      <h3 class="text-right"> {{number_format($bill_due_date)}} </h3>

                  </div>
                  <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <!-- ./col -->
            </div>
        </x-card-collapsible>
            @endif


        {{--  --}}
          </div>
        </section>
        <!-- /.content -->
      </div>
      <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
      <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</x-content>

@endsection
