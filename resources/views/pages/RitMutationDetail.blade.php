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
                                :options="$options['projects']"
                                :value="$data->project_id"
                                :disabled="true"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Pengendara'"
                                :placeholder="'Pilih Pengendara'"
                                :col="4"
                                :name="'driver_id'"
                                :options="$options['drivers']"
                                :value="$data->driver_id"
                                :disabled="true"
                                :required="false"></x-in-select>
                            <x-in-select
                                :label="'Mutasi Material'"
                                :placeholder="'Pilih Mutasi Material'"
                                :col="4"
                                :name="'material_mutation_id'"
                                :options="$options['material_mutations']"
                                :value="$data->material_mutation_id"
                                :disabled="true"
                                :required="false"></x-in-select>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Jumlah'"
                                :col="4"
                                :name="'amount'"
                                :value="$data->amount"
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

    <meta name="search-branch" content="{{ $data->branch_id}}">
    <meta name="search-project" content="{{ $data->project_id}}">
    <meta name="search-driver" content="{{ $data->driver_id }}">
    <meta name="search-material-mutation" content="{{ $data->material_mutation_id }}">
    <meta name="search-date-start" content="{{ app('request')->input('date_start') ?? null }}">
    <meta name="search-date-finish" content="{{ app('request')->input('date_finish') ?? null }}">

    <meta name="old-branch" content="{{ old('branch_id') ?? null }}">
    <meta name="old-project" content="{{ old('project_id') ?? null }}">
    <meta name="old-driver" content="{{ old('driver_id') ?? null }}">
    <meta name="old-material-mutation" content="{{ old('material_mutation') ?? null }}">
    <meta name="old-amount" content="{{ old('amount') ?? null }}">
    <meta name="old-created" content="{{ old('created') ?? null }}">
    <meta name="url-branch" content="{{ route('branch.index') }}">
    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-driver" content="{{ route('driver.index') }}">
    <meta name="url-material-mutation" content="{{ route('material-mutation.index') }}">


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

    {{-- Form --}}
    <script>
        $(function () {
            let selectBranchIn = $('#in_branch_id');
            let selectProjectIn = $('#in_project_id');
            let selectDriverIn = $('#in_driver_id');
            let selectMaterialMutationIn = $('#in_material_mutation_id');

            selectBranchIn.on('change', function () {
                let branchId = $(this).val();
                let projectId = selectProjectIn.val();
                let searchDriverIn = $('meta[name="search-material-mutation"]').attr('content');
                let searchMaterialMutation = $('meta[name="search-material-mutation"]').attr('content');

                if (branchId == '') {
                    selectDriverIn.empty();
                    selectDriverIn.append('<option value="">Pilih Pengendara</option>');

                    selectMaterialMutationIn.empty();
                    selectMaterialMutationIn.append('<option value="">Pilih Mutasi Material</option>');

                    return;
                }

                // Get driver
                $.ajax({
                    url: $('meta[name="url-driver"]').attr('content'),
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                    },
                    success: function (data) {
                        let oldDriver = $('meta[name="old-driver"]').attr('content');

                        selectDriverIn.empty();
                        selectDriverIn.append(`<option value="">Pilih Pengendara</option>`);

                        data.datas.forEach(function(item) {
                            selectDriverIn.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectDriverIn.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Pengendara',
                            allowClear: true,
                        });

                        if (oldDriver != '') {
                            selectDriverIn.val(oldDriver).trigger('change');
                        }
                    }
                });

                //get project
                $.ajax({
                    url: $('meta[name="url-project"]').attr('content'),
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                    },
                    success: function (data) {
                        let oldProject = $('meta[name="old-project"]').attr('content');

                        selectProjectIn.empty();
                        selectProjectIn.append(`<option value="">Pilih Proyek</option>`);

                        data.datas.forEach(function(item) {
                            selectProjectIn.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectProjectIn.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Proyek',
                            allowClear: true,
                        });

                        if (oldProject != '') {
                            selectProjectIn.val(oldProject).trigger('change');
                        }
                    }
                });

                // Get material mutation
                $.ajax({
                    url: $('meta[name="url-material-mutation"]').attr('content'),
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                        type: 'out',
                    },
                    success: function (data) {
                        let oldMaterialMutation = $('meta[name="old-material-mutation"]').attr('content');

                        selectMaterialMutationIn.empty();
                        selectMaterialMutationIn.append(`<option value="">Pilih Mutasi Material</option>`);

                        data.datas.forEach(function(item) {
                            selectMaterialMutationIn.append(`<option value="${item.id}">${item.ref_no}</option>`);
                        });

                        selectMaterialMutationIn.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Mutasi Material',
                            allowClear: true,
                        });

                        if (oldMaterialMutation != '') {
                            selectMaterialMutationIn.val(oldMaterialMutation).trigger('change');
                        }
                    }
                });
            });

            if (selectBranchIn.val() != '')
                selectBranchIn.trigger('change');
        });
    </script>
@endpush
