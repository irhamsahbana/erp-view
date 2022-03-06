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

@section('content-header', 'Detail Mutasi Material')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('material-mutation.store') }}" method="POST">

                        @csrf
                        @method('POST')
                        <input type="hidden" name="id" value="{{ $data->id }}">

                        <x-row>
                            <x-in-select
                                :label="'Cabang'"
                                :placeholder="'Pilih Cabang'"
                                :col="12"
                                :name="'branch_id'"
                                :options="$options['branches']"
                                :value="$data->branch_id"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Proyek'"
                                :placeholder="'Pilih Proyek'"
                                :col="12"
                                :name="'project_id'"
                                :value="$data->project_id"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Material'"
                                :placeholder="'Pilih Material'"
                                :col="12"
                                :name="'material_id'"
                                :value="$data->material_id"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Pengendara'"
                                :placeholder="'Pilih Pengendara'"
                                :col="12"
                                :name="'driver_id'"
                                :value="$data->driver_id"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Jenis'"
                                :placeholder="'Pilih Jenis'"
                                :col="12"
                                :id="'in_type'"
                                :name="'type'"
                                :options="$options['types']"
                                :value="$data->type == 1 ? 'in' : 'out'"
                                :required="true"></x-in-select>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Harga Material'"
                                :col="4"
                                :name="'material_price'"
                                :value="$data->material_price"
                                :required="true"></x-in-text>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Volume'"
                                :col="4"
                                :name="'volume'"
                                :value="$data->volume"
                                :required="true"></x-in-text>
                            <x-in-text
                                :type="'number'"
                                :step="'0.01'"
                                :label="'Biaya'"
                                :col="4"
                                :name="'cost'"
                                :value="$data->cost"
                                :required="true"></x-in-text>
                            <x-in-text
                                :type="'date'"
                                :label="'Tanggal'"
                                :col="12"
                                :name="'created'"
                                :value="$data->created"
                                :required="true"></x-in-text>

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
    <meta name="data-material" content={{ $data->material_id ?? null }}>
    <meta name="data-driver" content={{ $data->driver_id ?? null }}>


    <meta name="url-branch" content="{{ route('branch.index') }}">
    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-material" content="{{ route('material.index') }}">
    <meta name="url-driver" content="{{ route('driver.index') }}">


    {{-- Searching --}}
    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            let selectDriver = $('#driver_id');
            let selectMaterial = $('#material_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let dataProject = $('meta[name=data-project]').attr('content');
                let dataDriver = $('meta[name=data-driver]').attr('content');

                if (branchId == '') {
                    selectProject.empty();
                    selectProject.append('<option value="">Pilih Proyek</option>');

                    selectDriver.empty();
                    selectDriver.append('<option value="">Pilih Pengendara</option>');

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

            // Get material
            function loadMaterial() {
                selectMaterial.select2({
                    theme: 'bootstrap4',
                    placeholder: 'Pilih Material',
                    allowClear: true,
                    async: false,
                    ajax: {
                        url: $('meta[name="url-material"]').attr('content'),
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            let results = data.datas.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                };
                            });

                            return {
                                results: results
                            };
                        },
                        cache: true
                    }
                });
            }

            loadMaterial();

            let dataMaterial = $('meta[name="data-material"]').attr('content');
            if (dataMaterial != '') {
                loadMaterial();

                $.ajax({
                    type: 'GET',
                    url: `${$('meta[name="url-material"]').attr('content')}/${dataMaterial}`,
                }).then(function (data) {
                    let option = new Option(data.data.name, data.data.id, true, true);
                    selectMaterial.append(option).trigger('change');

                    let result = {
                        id: data.data.id,
                        text: data.data.name,
                    };

                    selectMaterial.trigger({
                        type: 'select2:select',
                        params: {
                            data: result
                        }
                    });
                });
            }

            if (selectBranch.val() != '')
                selectBranch.trigger('change');
        });
    </script>
@endpush
