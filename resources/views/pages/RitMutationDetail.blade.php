@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Detail Mutasi Hutang Ritase'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Detail Mutasi Hutang Ritase')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('rit-mutation.store') }}" method="POST">

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
                                :label="'Pengendara'"
                                :placeholder="'Pilih Pengendara'"
                                :col="4"
                                :name="'driver_id'"
                                :disabled="true"
                                :required="false"></x-in-select>
                            <x-in-select
                                :label="'Jenis Transaksi'"
                                :placeholder="'Pilih Jenis Transaksi'"
                                :col="4"
                                :name="'transaction_type'"
                                :options="$options['transactionTypes']"
                                :value="$data->transaction_type"
                                :disabled="true"
                                :required="true"></x-in-select>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Jumlah'"
                                :col="4"
                                :name="'amount'"
                                :value="$data->amount"
                                :required="true"></x-in-text>
                            <x-in-text
                                :type="'date'"
                                :label="'Tanggal'"
                                :col="4"
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
    <meta name="data-driver" content={{ $data->driver_id ?? null }}>


    <meta name="url-branch" content="{{ route('branch.index') }}">
    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-driver" content="{{ route('driver.index') }}">


    {{-- Searching --}}
    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            let selectDriver = $('#driver_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let dataProject = $('meta[name=data-project]').attr('content');
                let dataDriver = $('meta[name=data-driver]').attr('content');

                if (branchId == '') {
                    selectProject.empty();
                    selectProject.append('<option value="">Pilih Proyek</option>');

                    selectDriver.empty();
                    selectDriver.append('<option value="">Pilih Driver</option>');

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
                    url: $('meta[name="url-driver"]').attr('content'),
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                    },
                    success: function (data) {
                        selectDriver.empty();
                        selectDriver.append(`<option value="">Pilih Pengendara</option>`);

                        data.datas.forEach(function(item) {
                            selectDriver.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectDriver.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Pengendara',
                            allowClear: true,
                        });

                        if (dataDriver != '') {
                            selectDriver.val(dataDriver).trigger('change');
                        }
                    }
                });
            });

            if (selectBranch.val() != '')
                selectBranch.trigger('change');
        });
    </script>
@endpush
