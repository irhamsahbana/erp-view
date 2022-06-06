@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Piutang Usaha'
        ],
    ];

    function abcd()
    {

    }
@endphp

@section('content-header', 'Account Receivable')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible :title="'Pencarian'">
                <form style="width: 100%">
                    <x-row>
                        <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="4"
                            :name="'branch_id'"
                            {{-- :id="'branch_id'" --}}
                            :options="$options['branches']"
                            {{-- name = 'idBranch' --}}
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Project'"
                            :placeholder="'Pilih Project'"
                            :col="4"
                            :name="'project_id'"
                            :value="app('request')->input('project_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Vendor'"
                            :placeholder="'Pilih Vendor'"
                            :col="4"
                            :name="'receivable_vendor_id'"
                            :value="app('request')->input('receivable_vendor_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Kirim dari'"
                            :col="6"
                            :value="app('request')->input('send_date_start') ?? null"
                            :name="'send_date_start'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Kirim sampai:'"
                            :col="6"
                            :value="app('request')->input('send_date_finish') ?? null"
                            :name="'send_date_finish'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Bayar Dari:'"
                            :col="6"
                            :value="app('request')->input('pay_date_start') ?? null"
                            :name="'pay_date_finish'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Bayar sampai:'"
                            :col="6"
                            :value="app('request')->input('pay_date_finish') ?? null"
                            :name="'pay_date_finish'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Batas Waktu Dari:'"
                            :col="6"
                            :value="app('request')->input('due_date_start') ?? null"
                            :name="'due_date_start'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Batas Waktu sampai:'"
                            :col="6"
                            :value="app('request')->input('due_date_finish') ?? null"
                            :name="'due_date_finish'"></x-in-text>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('receivable.index') }}">reset</a>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>
            <x-card-collapsible :title="'Total Saldo'">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="small-box bg-info">
                          <div class="inner">
                              <p>Piutang Belum Terbayarkan</p>
                            <h3 class="text-right"> {{number_format($receivable)}} </h3>

                          </div>

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


                        </div>
                      </div>
                      <!-- ./col -->
                    </div>

                  </div><!-- /.container-fluid -->
        </x-card-collapsible>
            <x-card-collapsible :title="'Data Mutasi'">
                <x-row>
                    <x-col class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                    </x-col>

                    <x-col>
                        <x-table :thead="['Cabang', 'Proyek','Tanggal Kirim', 'Batas Waktu','Tanggal Bayar' ,  'Ref',  'Vendor', 'Keterangan', 'Jumlah' ,'Status Bayar','Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->project->name }}</td>
                                    <td>{{ date("d-m-Y", strtotime($data->send_date)) }}</td>
                                    <td>{{ date("d-m-Y", strtotime($data->due_date)) }}</td>
                                    <td>

                                        @if($data->pay_date)
                                            {{ date("d-m-Y", strtotime($data->pay_date)) }}

                                        @endif
                                        </td>
                                    <td>{{ $data->ref_no }}</td>
                                    <td>{{ $data->receivable_vendor->name }}</td>
                                    <td>{{ $data->notes }}</td>
                                    <td class="text-right">{{ number_format($data->amount) }}</td>
                                    <td class="text-center">
                                        @if ( $data->is_paid)
                                            <form action="{{ route('receivable-statuspaid.post', $data->id) }}" style="display:inline!important;" method="POST">
                                                @method('POST')
                                                @csrf
                                            <button
                                            type="submit"
                                            class="btn btn-{{ $data->is_paid == false ? 'danger' : 'success' }}"
                                                onclick="return confirm('Apakah anda ingin mengubah status pembayaran ini?')"
                                                title="ubah status"><i class="{{ $data->is_paid == false ? 'fas fa-times-circle' : 'fas fa-check-circle' }}"></i></button>
                                        </form>
                                         @else
                                            <button
                                            class="btn btn-{{ $data->is_paid == false ? 'danger' : 'success' }}"
                                            onclick="changeStatus({{$data->id}})"
                                                title="ubah status"><i class="{{ $data->is_paid == false ? 'fas fa-times-circle' : 'fas fa-check-circle' }}"></i></button>


                                        @endif
                                    </td>
                                    <td>
                                        <form
                                        style=" display:inline!important;"
                                        method="POST"
                                        action="{{ route('receivable.delete', $data->id) }}">
                                            @csrf
                                            @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger"
                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                            title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    </form>

                                    </td>
                                    {{-- <td>{{ $data->pay_date }}</td> --}}

                                </tr>
                            @endforeach
                        </x-table>
                    </x-col>

                    <x-col class="d-flex justify-content-end">
                        {{ $datas->links() }}
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    <x-modal :title="'Tambah Data'" :id="'add-modal'">
        <form style="width: 100%" action="{{ route('receivable.add') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="4"
                            :name="'new_branch_id'"
                            {{-- :id="'branch_id'" --}}
                            :options="$options['branches']"
                            {{-- name = 'idBranch' --}}
                            :value="app('request')->input('branch_id') ?? null"
                            :required="true"></x-in-select>
                        <x-in-select
                            :label="'Project'"
                            :placeholder="'Pilih Project'"
                            :col="4"
                            :name="'new_project_id'"
                            :value="app('request')->input('project_id') ?? null"
                            :required="true"></x-in-select>
                        <x-in-select
                            :label="'Vendor'"
                            :placeholder="'Pilih Vendor'"
                            :col="4"
                            :name="'new_receivable_vendor_id'"
                            :value="app('request')->input('receivable_vendor_id') ?? null"
                            :required="true"></x-in-select>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Kirim'"
                            :name="'new_send_date'"
                            :col="4"
                            :required="true"
                            ></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Batas Waktu'"
                            :name="'new_due_date'"
                            :col="4"
                            :required="true"
                            ></x-in-text>
                        <x-in-text
                            :type="'number'"
                            :label="'Jumlah'"
                            :name="'new_amount'"
                            :col="4"
                            :required="true"
                            ></x-in-text>
                        <x-in-text
                            :type="'text'"
                            :label="'Keterangan'"
                            :name="'new_notes'"
                            :col="12"
                            :required="true"
                            ></x-in-text>

                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>

    <x-modal :title="'Change Pay Status'" :id="'modal-change-status'" :size="'md'">
        <form style="width: 100%" action="" method="POST" id="form-status-change">
            @csrf
            @method('POST')

            <x-row>
                <input type="hidden" name="id" id="order_id" value="">
                <x-in-text
                    :type="'date'"
                    :label="'Tanggal'"
                    :name="'pay_date'"
                    :required="true"
                    ></x-in-text>
                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah anda yakin ingin mengubah status order data ini?')">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
@endsection

@push('js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-order-show" content="{{ route('order.show', 'dummy-id') }}">

    <input hidden id="search-project" value="{{app('request')->input('project_id') ?? null }}">
   <input hidden id='url-project' value="{{route('project.index')}}">

   <input hidden id='search-vendor' value="{{app('request')->input('receivable_vendor_id') ?? null }}">
   <input hidden id='url-vendor' value="{{route('receivable-vendor.index')}}">
{{-- Modal --}}
   <input hidden id="new_search-project" value="{{app('request')->input('project_id') ?? null }}">
   <input hidden id='new_url-project' value="{{route('project.index')}}">

   <input hidden id='new_search-vendor' value="{{app('request')->input('receivable_vendor_id') ?? null }}">
   <input hidden id='new_url-vendor' value="{{route('receivable-vendor.index')}}">
   <meta name="url-order-change-status" content="{{ route('receivable-statuspaid.post', 'dummy-id') }}">

   <script>
        function changeStatus(id) {
            $('#modal-change-status').modal('show');
            $('#form-status-change').trigger('reset');

            let url = $('meta[name="url-order-show"]').attr('content');
            url = url.replace('dummy-id', id);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                cache: false,
                success: function(data) {
                    $('#pay_date').val(data.pay_date);

                    //change form action
                    let url = $('meta[name="url-order-change-status"]').attr('content');
                    url = url.replace('dummy-id', data.id);
                    $('#form-status-change').attr('action', url);

                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        $(function() {
            let selectProject =  $('#project_id')
            let selectBranch = $('#branch_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let searchProject = $('#search-project').val();

                if (branchId == '') {
                    selectProject.empty();
                    selectProject.append('<option value="">Pilih Proyek</option>');
                    return;
                }
            $.ajax({
                url: $('#url-project').val(),
                type: 'GET',
                data: {
                    branch_id : branchId,
                },
                success: function(data) {
                    selectProject.empty()
                    selectProject.append(`<option value="">Pilih Proyek</option>`);

                    data.datas.forEach(item =>
                        selectProject.append(`<option value="${item.id}">${item.name} </option>`)
                    );
                    if (searchProject != '')
                            selectProject.val(searchProject).trigger('change');
                }
            })

            })
        });
        $(function() {
            let selectBranch = $('#branch_id');
            let selectVendor =  $('#receivable_vendor_id')

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let searchVendor = $('#search-vendor').val();

                if (branchId == '') {
                    selectVendor.empty();
                    selectVendor.append('<option value="">Pilih Vendor</option>');
                    return;
                }
            $.ajax({
                url: $('#url-vendor').val(),
                type: 'GET',
                data: {
                    branch_id : branchId,
                },
                success: function(data) {
                    selectVendor.empty()
                    selectVendor.append(`<option value="">Pilih Vendor</option>`);

                    data.datas.forEach(item =>
                        selectVendor.append(`<option value="${item.id}">${item.name} </option>`)
                    );
                    if (searchVendor != '')
                            selectVendor.val(searchProject).trigger('change');
                }
            })

            })
        });
        // modal

        $(function() {
            let selectProject =  $('#new_project_id')
            let selectBranch = $('#new_branch_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let searchProject = $('#new_search-project').val();

                if (branchId == '') {
                    selectProject.empty();
                    selectProject.append('<option value="">Pilih Proyek</option>');
                    return;
                }
            $.ajax({
                url: $('#new_url-project').val(),
                type: 'GET',
                data: {
                    branch_id : branchId,
                },
                success: function(data) {
                    selectProject.empty()
                    selectProject.append(`<option value="">Pilih Proyek</option>`);

                    data.datas.forEach(item =>
                        selectProject.append(`<option value="${item.id}">${item.name} </option>`)
                    );
                    if (searchProject != '')
                            selectProject.val(searchProject).trigger('change');
                }
            })

            })
        });
        $(function() {
            let selectVendor =  $('#new_receivable_vendor_id')
            let selectBranch = $('#new_branch_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let searchVendor = $('#new_search-vendor').val();

                if (branchId == '') {
                    selectVendor.empty();
                    selectVendor.append('<option value="">Pilih Vendor</option>');
                    return;
                }
            $.ajax({
                url: $('#new_url-vendor').val(),
                type: 'GET',
                data: {
                    branch_id : branchId,
                },
                success: function(data) {
                    selectVendor.empty()
                    selectVendor.append(`<option value="">Pilih Vendor</option>`);

                    data.datas.forEach(item =>
                        selectVendor.append(`<option value="${item.id}">${item.name} </option>`)
                    );
                    if (searchVendor != '')
                            selectVendor.val(searchProject).trigger('change');
                }
            })

            })
        });
    </script>
<style>
    th {
        text-align: center
    }
</style>
@endpush

