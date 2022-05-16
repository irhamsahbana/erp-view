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

                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('receivable.index') }}">reset</a>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>

            <x-card-collapsible>
                <x-row>


                    <x-col>
                        <x-table :thead="['Cabang', 'Proyek','Vendor', 'Jumlah']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->project->name }}</td>
                                    <td>{{ $data->receivable_vendor->name }}</td>
                                    <td class="text-right">{{ number_format($data->amount) }}</td>


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

@endsection

@push('js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url-order-show" content="{{ route('order.show', 'dummy-id') }}">

    <input hidden id="search-project" value="{{app('request')->input('project_id') ?? null }}">
   <input hidden id='url-project' value="{{route('project.index')}}">

   <input hidden id='search-vendor' value="{{app('request')->input('receivable_vendor_id') ?? null }}">
   <input hidden id='url-vendor' value="{{route('receivable-vendor.index')}}">
{{-- Modal --}}
 <meta name="url-order-change-status" content="{{ route('receivable-statuspaid.post', 'dummy-id') }}">

   <script>


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

    </script>
<style>
    th {
        text-align: center
    }
</style>
@endpush

