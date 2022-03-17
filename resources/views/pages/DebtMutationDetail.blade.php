@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Detail Mutasi Material'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Detail Mutasi Hutang')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('debt-mutation.store') }}" method="POST">

                        @csrf
                        @method('POST')
                        <input type="hidden" name="id" value="{{ $data->id }}">

                        <x-row>
                            <x-in-select
                                :label="'Cabang'"
                                :placeholder="'Pilih Cabang'"
                                :col="4"
                                :name="'branch_id'"
                                :options="$options['branches']"
                                :value="$data->branch_id"
                                :disabled="true"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Proyek'"
                                :placeholder="'Pilih Proyek'"
                                :col="4"
                                :name="'project_id'"
                                :value="$data->project_id"
                                :disabled="true"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Vendor'"
                                :placeholder="'Pilih Vendor'"
                                :col="4"
                                :name="'vendor_id'"
                                :disabled="true"
                                :required="false"></x-in-select>
                            <x-in-select
                                :label="'Jenis'"
                                :placeholder="'Pilih Jenis'"
                                :col="6"
                                :name="'type'"
                                :options="$options['types']"
                                :value="$data->type"
                                :disabled="true"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Jenis Transaksi'"
                                :placeholder="'Pilih Jenis Transaksi'"
                                :col="6"
                                :name="'transaction_type'"
                                :options="$options['transactionTypes']"
                                :value="$data->transaction_type"
                                :disabled="true"
                                :required="true"></x-in-select>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Jumlah'"
                                :col="6"
                                :name="'amount'"
                                :value="$data->amount"
                                :required="true"></x-in-text>
                            <x-in-text
                                :type="'date'"
                                :label="'Tanggal'"
                                :col="6"
                                :name="'created'"
                                :value="$data->created"
                                :required="true"></x-in-text>
                            <x-in-text
                                :label="'Catatan'"
                                :name="'notes'"
                                :value="$data->notes"></x-in-text>

                            <x-col class="text-right">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </x-col>
                        </x-row>
                    </form>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-branch" content="{{ Auth::user()->branch_id ?? null }}">

    <meta name="data-project" content={{ $data->project_id ?? null }}>
    <meta name="data-project" content={{ $data->project_id ?? null }}>
    <meta name="data-vendor" content={{ $data->vendor_id ?? null }}>


    <meta name="url-branch" content="{{ route('branch.index') }}">
    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-vendor" content="{{ route('vendor.index') }}">


    {{-- Searching --}}
    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            let selectVendor = $('#vendor_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let dataProject = $('meta[name=data-project]').attr('content');
                let dataVendor = $('meta[name=data-vendor]').attr('content');

                if (branchId == '') {
                    selectProject.empty();
                    selectProject.append('<option value="">Pilih Proyek</option>');

                    selectVendor.empty();
                    selectVendor.append('<option value="">Pilih Vendor</option>');

                    return;
                }

                // Get project
                $.ajax({
                    url: $('meta[name="url-project"]').attr('content'),
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                    },
                    success: function (data) {
                        selectProject.empty();
                        selectProject.append(`<option value="">Pilih Proyek</option>`);

                        data.datas.forEach(function(item) {
                            selectProject.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectProject.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Proyek',
                            allowClear: true,
                        });

                        if (dataProject != '') {
                            selectProject.val(dataProject).trigger('change');
                        }
                    }
                });

                // Get driver
                $.ajax({
                    url: $('meta[name="url-vendor"]').attr('content'),
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                    },
                    success: function (data) {
                        selectVendor.empty();
                        selectVendor.append(`<option value="">Pilih Vendor</option>`);

                        data.datas.forEach(function(item) {
                            selectVendor.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectVendor.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Vendor',
                            allowClear: true,
                        });

                        if (dataVendor != '') {
                            selectVendor.val(dataVendor).trigger('change');
                        }
                    }
                });
            });

            if (selectBranch.val() != '')
                selectBranch.trigger('change');
        });
    </script>
@endpush
