@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Saldo Hutang Ritase'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Saldo Hutang Ritase')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible :title="'Pencarian'" :collapse="true">
                <form style="width: 100%">
                    <x-row>
                        <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="3"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Proyek'"
                            :placeholder="'Pilih Proyek'"
                            :col="3"
                            :name="'project_id'"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Pengendara'"
                            :placeholder="'Pilih Pengendara'"
                            :col="3"
                            :name="'driver_id'"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Mutasi Material'"
                            :placeholder="'Pilih Mutasi Material'"
                            :col="3"
                            :name="'material_mutation_id'"
                            :required="false"></x-in-select>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('rit-mutation.balance') }}">reset</a>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>

            <x-card-collapsible>
                <x-row>
                    <x-col>
                        <x-table :thead="['Cabang', 'Proyek', 'Pengendara', 'Total']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->project->name }}</td>
                                    <td>{{ $data->driver->name }}</td>
                                    <td>{{ 'Rp. ' . number_format($data->total, 2) }}</td>
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
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-branch" content="{{ Auth::user()->branch_id ?? null }}">

    <meta name="search-branch" content="{{ app('request')->input('branch_id') ?? null }}">
    <meta name="search-driver" content="{{ app('request')->input('driver_id') ?? null }}">
    <meta name="search-material-mutation" content="{{ app('request')->input('material_mutation') ?? null }}">

    <meta name="url-branch" content="{{ route('branch.index') }}">
    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-driver" content="{{ route('driver.index') }}">
    <meta name="url-material-mutation" content="{{ route('material-mutation.index') }}">

    <meta name="">

    {{-- Searching --}}
    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            let selectDriver = $('#driver_id');
            let selectMaterialMutation = $('#material_mutation_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let projectId = selectProject.val();
                let searchDriver = $('meta[name="search-material-mutation"]').attr('content');
                let searchProject = $('meta[name="search-project"]').attr('content');
                let searchMaterialMutation = $('meta[name="search-material-mutation"]').attr('content');

                if (branchId == '') {
                    selectProject.empty();
                    selectProject.append('<option value="">Pilih Proyek</option>');

                    selectDriver.empty();
                    selectDriver.append('<option value="">Pilih Pengendara</option>');

                    selectMaterialMutation.empty();
                    selectMaterialMutation.append('<option value="">Pilih Mutasi Material</option>');

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

                        if (searchDriver != '') {
                            selectDriver.val(searchDriver).trigger('change');
                        }
                    }
                });

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

                        if (searchProject != '') {
                            selectProject.val(searchProject).trigger('change');
                        }
                    }
                });

                // Get material mutation
                $.ajax({
                    url: $('meta[name="url-material-mutation"]').attr('content'),
                    type: 'GET',
                    data: {
                        project_id: projectId,
                        type: 'out'
                    },
                    success: function (data) {
                        selectMaterialMutation.empty();
                        selectMaterialMutation.append(`<option value="">Pilih Mutasi Material</option>`);

                        data.datas.forEach(function(item) {
                            selectMaterialMutation.append(`<option value="${item.id}">${item.ref_no}</option>`);
                        });

                        selectMaterialMutation.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Mutasi Material',
                            allowClear: true,
                        });

                        if (searchMaterialMutation != '') {
                            selectMaterialMutation.val(searchMaterialMutation).trigger('change');
                        }
                    }
                });
            });

            if (selectBranch.val() != '')
                selectBranch.trigger('change');
        });
    </script>
@endpush
