@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Receivable Balance'
        ],
    ];
@endphp

@section('content-header', 'Account Receivable Balance')

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
                            :name="'date_start'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Kirim sampai:'"
                            :col="6"
                            :value="app('request')->input('send_date_finish') ?? null"
                            :name="'date_finish'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Bayar Dari:'"
                            :col="6"
                            :value="app('request')->input('pay_date_start') ?? null"
                            :name="'date_finish'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Bayar sampai:'"
                            :col="6"
                            :value="app('request')->input('pay_date_finish') ?? null"
                            :name="'date_finish'"></x-in-text>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('receivable.index') }}">reset</a>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>

            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                    </x-col>

                    <x-col>
                        <x-table :thead="['Tanggal Kirim','Tanggal Bayar' , 'Ref', 'Cabang', 'Proyek', 'Vendor', 'Keterangan', 'Jumlah' ,'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->created }}</td>
                                    <td>{{ $data->ref_no }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td class="text-right">{{  number_format($data->amount) }}</td>
                                    <td>{{ $data->notes }}</td>
                                    <td>
                                        @if($data->status == '1')
                                            Waiting
                                        @elseif($data->status == '2')
                                            Accepted
                                        @elseif($data->status == '3')
                                            Rejected
                                        @elseif($data->status == '4')
                                            Hold
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->is_open)
                                            <span class="badge badge-success">Open</span>
                                        @else
                                            <span class="badge badge-danger">Close</span>
                                        @endif
                                    </td>
                                    <td>


                                    </td>
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

@endsection

@push('js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-order-show" content="{{ route('order.show', 'dummy-id') }}">
    <meta name="url-order-change-status" content="{{ route('order.change-order-status', 'dummy-id') }}">

    <input hidden id="search-project" value="{{app('request')->input('project_id') ?? null }}">
   <input hidden id='url-project' value="{{route('project.index')}}">

   <input hidden id='search-vendor' value="{{app('request')->input('receivable_vendor_id') ?? null }}">
   <input hidden id='url-vendor' value="{{route('receivable-vendor.index')}}">
{{-- Modal --}}
   <input hidden id="modal_search-project" value="{{app('request')->input('project_id') ?? null }}">
   <input hidden id='modal_url-project' value="{{route('project.index')}}">

   <input hidden id='modal_search-vendor' value="{{app('request')->input('receivable_vendor_id') ?? null }}">
   <input hidden id='modal_url-vendor' value="{{route('receivable-vendor.index')}}">

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
                    $('#order_id').val(data.id);
                    $('#in_status').val(data.status);
                    $('#info_branch').val(data.branch_id);
                    $('#info_user').val(data.username);
                    $('#info_amount').val(data.amount);
                    $('#info_created').val(data.created);

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
            let selectVendor =  $('#receivable_vendor_id')
            let selectBranch = $('#branch_id');

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
            let selectProject =  $('#modal_project_id')
            let selectBranch = $('#modal_branch_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let searchProject = $('#modal_search-project').val();

                if (branchId == '') {
                    selectProject.empty();
                    selectProject.append('<option value="">Pilih Proyek</option>');
                    return;
                }
            $.ajax({
                url: $('#modal_url-project').val(),
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
            let selectVendor =  $('#modal_receivable_vendor_id')
            let selectBranch = $('#modal_branch_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let searchVendor = $('#modal_search-vendor').val();

                if (branchId == '') {
                    selectVendor.empty();
                    selectVendor.append('<option value="">Pilih Vendor</option>');
                    return;
                }
            $.ajax({
                url: $('#modal_url-vendor').val(),
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
@endpush
