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
            <div class="row">
              <div class="col-lg-4">
                <!-- small box -->
                {{-- Piutang --}}
                {{-- Total Piutang --}}
                <div class="small-box bg-info">
                  <div class="inner">
                      <p>Piutang Belum Terbayarkan</p>
                    <h3 class="text-right"> {{number_format($receivable)}} </h3>

                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <!-- ./col -->
              {{-- <div class="col-lg-4">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3>53<sup style="font-size: 20px">%</sup></h3>

                    <p>Bounce Rate</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div> --}}
              <!-- ./col -->
              <div class="col-lg-4">
                <!-- small box -->
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3>44</h3>

                    <p>User Registrations</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-4">
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
            <!-- /.row -->
            <!-- Main row -->

            <!-- /.row (main row) -->
          </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
      </div>
</x-content>


@endsection
