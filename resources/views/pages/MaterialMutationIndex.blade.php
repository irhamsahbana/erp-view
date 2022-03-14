@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Mutasi Material'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Mutasi Material')

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
                            :col="6"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Proyek'"
                            :placeholder="'Pilih Proyek'"
                            :col="6"
                            :name="'project_id'"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Material'"
                            :placeholder="'Pilih Material'"
                            :col="6"
                            :name="'material_id'"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Pengendara'"
                            :placeholder="'Pilih Pengendara'"
                            :col="6"
                            :name="'driver_id'"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Status'"
                            :placeholder="'Pilih Status'"
                            :col="6"
                            :name="'is_open'"
                            :options="$options['status']"
                            :value="app('request')->input('is_open') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Jenis Mutasi'"
                            :placeholder="'Pilih Jenis Mutasi'"
                            :col="6"
                            :name="'type'"
                            :options="$options['types']"
                            :value="app('request')->input('type') ? app('request')->input('type') : ''"
                            :required="false"></x-in-select>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Mulai'"
                            :col="6"
                            :value="app('request')->input('date_start') ?? null"
                            :name="'date_start'"></x-in-text>
                        <x-in-text
                            :type="'date'"
                            :label="'Tanggal Selesai'"
                            :col="6"
                            :value="app('request')->input('date_finish') ?? null"
                            :name="'date_finish'"></x-in-text>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('material-mutation.index') }}">reset</a>
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
                        <x-table :thead="['Tanggal', 'Cabang', 'proyek', 'Material', 'Pengendara', 'Jenis', 'Harga Material', 'Volume', 'Biaya', 'Status', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->created }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->project->name }}</td>
                                    <td>{{ $data->material->name }}</td>
                                    <td>{{ $data->driver->name ?? null }}</td>
                                    <td>
                                        @if($data->type)
                                            Masuk
                                        @else
                                            Keluar
                                        @endif
                                    </td>
                                    <td>{{ 'Rp. ' . number_format($data->material_price, 2) }}</td>
                                    <td>{{ $data->volume }}</td>
                                    <td>{{ 'Rp. ' . number_format($data->cost, 2) }}</td>
                                    <td>
                                        @if($data->is_open)
                                            <span class="badge badge-success">Open</span>
                                        @else
                                            <span class="badge badge-danger">Close</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->is_open)
                                            <a
                                                href="{{ route('material-mutation.show', $data->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                            <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('material-mutation.destroy', $data->id) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                    title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        @endif
                                        @if(Auth::user()->role == 'owner')
                                            <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('material-mutation.change-status', $data->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                <button
                                                    type="submit"
                                                    class="btn btn-secondary"
                                                    onclick="return confirm('Apakah anda yakin ingin mengubah staus data ini?')"
                                                    title="Ubah"><i class="fas fa-sync-alt"></i></button>
                                            </form>
                                        @endif
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

    <x-modal :title="'Tambah Data'" :id="'add-modal'">
        <form style="width: 100%" action="{{ route('material-mutation.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                    :label="'Cabang'"
                    :placeholder="'Pilih Cabang'"
                    :col="6"
                    :id="'in_branch_id'"
                    :name="'branch_id'"
                    :options="$options['branches']"
                    :value="old('branch_id')"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Proyek'"
                    :placeholder="'Pilih Proyek'"
                    :col="6"
                    :id="'in_project_id'"
                    :name="'project_id'"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Material'"
                    :placeholder="'Pilih Material'"
                    :col="4"
                    :id="'in_material_id'"
                    :name="'material_id'"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Pengendara'"
                    :placeholder="'Pilih Pengendara'"
                    :col="4"
                    :id="'in_driver_id'"
                    :name="'driver_id'"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Jenis'"
                    :placeholder="'Pilih Jenis'"
                    :col="4"
                    :id="'in_type'"
                    :name="'type'"
                    :options="$options['types']"
                    :value="old('type')"
                    :required="true"></x-in-select>
                <x-in-text
                    :type="'number'"
                    :step="'0.01'"
                    :label="'Harga Material'"
                    :col="4"
                    :id="'in_material_price'"
                    :name="'material_price'"
                    :value="old('material_price')"
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'number'"
                    :step="'0.01'"
                    :label="'Volume'"
                    :col="4"
                    :id="'in_volume'"
                    :name="'volume'"
                    :value="old('volume')"
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'number'"
                    :step="'0.01'"
                    :label="'Biaya'"
                    :col="4"
                    :id="'in_cost'"
                    :name="'cost'"
                    :value="old('cost')"
                    :required="true"></x-in-text>
                <x-in-text
                    :type="'date'"
                    :label="'Tanggal'"
                    :col="12"
                    :id="'in_created'"
                    :name="'created'"
                    :required="true"></x-in-text>

                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-branch" content="{{ Auth::user()->branch_id ?? null }}">

    <meta name="search-branch" content="{{ app('request')->input('branch_id') ?? null }}">
    <meta name="search-project" content="{{ app('request')->input('project_id') ?? null }}">
    <meta name="search-material" content="{{ app('request')->input('material_id') ?? null }}">
    <meta name="search-driver" content="{{ app('request')->input('driver_id') ?? null }}">
    <meta name="search-date-start" content="{{ app('request')->input('date_start') ?? null }}">
    <meta name="search-date-finish" content="{{ app('request')->input('date_finish') ?? null }}">
    <meta name="search-status" content="{{ app('request')->input('status') ?? null }}">

    <meta name="old-branch" content="{{ old('branch_id') ?? null }}">
    <meta name="old-project" content="{{ old('project_id') ?? null }}">
    <meta name="old-material" content="{{ old('material_id') ?? null }}">
    <meta name="old-driver" content="{{ old('driver_id') ?? null }}">
    <meta name="old-create" content="{{ old('created') ?? null }}">
    <meta name="old-status" content="{{ old('is_open') ?? null }}">
    <meta name="old-material-price" content="{{ old('material_price') ?? null }}">
    <meta name="old-cost" content="{{ old('cost') ?? null }}">
    <meta name="old-type" content="{{ old('type') ?? null }}">

    <meta name="url-branch" content="{{ route('branch.index') }}">
    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-material" content="{{ route('material.index') }}">
    <meta name="url-driver" content="{{ route('driver.index') }}">

    <meta name="">

    {{-- Searching --}}
    <script>
        $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            let selectDriver = $('#driver_id');
            let selectMaterial = $('#material_id');

            let searchMaterial = $('meta[name="search-material"]').attr('content');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let searchProject = $('meta[name="search-project"]').attr('content');
                let searchDriver = $('meta[name="search-driver"]').attr('content');

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

                        if (searchProject != '') {
                            selectProject.val(searchProject).trigger('change');
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

                        console.log(searchDriver, 'searchDriver');

                        if (searchDriver != '') {
                            selectDriver.val(searchDriver).trigger('change');
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

            if (searchMaterial != '') {
                loadMaterial();

                $.ajax({
                    type: 'GET',
                    url: `${$('meta[name="url-material"]').attr('content')}/${searchMaterial}`,
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

    {{-- Form --}}
    <script>
        $(function () {
            let selectBranchIn = $('#in_branch_id');
            let selectProjectIn = $('#in_project_id');
            let selectDriverIn = $('#in_driver_id');
            let selectMaterialIn = $('#in_material_id');

            selectBranchIn.on('change', function () {
                let branchId = $(this).val();
                let searchProject = $('meta[name="search-project"]').attr('content');
                let searchDriver = $('meta[name="search-driver"]').attr('content');

                if (branchId == '') {
                    selectProjectIn.empty();
                    selectProjectIn.append('<option value="">Pilih Proyek</option>');

                    selectDriverIn.empty();
                    selectDriverIn.append('<option value="">Pilih Pengendara</option>');

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
            });

            // Get material
            function loadMaterialIn() {
                selectMaterialIn.select2({
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

            loadMaterialIn();

            let oldMaterial = $('meta[name="old-material"]').attr('content');
            if (oldMaterial != '') {
                loadMaterialIn();

                $.ajax({
                    type: 'GET',
                    url: `${$('meta[name="url-material"]').attr('content')}/${oldMaterial}`,
                }).then(function (data) {
                    let option = new Option(data.data.name, data.data.id, true, true);
                    selectMaterialIn.append(option).trigger('change');

                    let result = {
                        id: data.data.id,
                        text: data.data.name,
                    };

                    selectMaterialIn.trigger({
                        type: 'select2:select',
                        params: {
                            data: result
                        }
                    });
                });
            }

            if (selectBranchIn.val() != '')
                selectBranchIn.trigger('change');
        });
    </script>

    {{-- Functionality for disabled --}}
    <script>
        $(function () {
            const selectType = $('#in_type');
            const oldType = $('meta[name="old-type"]').attr('content');

                if (oldType == 'in') {
                    $( "#in_driver_id" ).prop( "disabled", true );
                    $( "#in_cost" ).prop( "disabled", true );
                } else if (oldType == 'out') {
                    $( "#in_material_price" ).prop( "disabled", true );
                }

            selectType.on('change', function() {
                if (this.value == 'in' || this.value == '') {
                    $( "#in_driver_id" ).prop( "disabled", true );
                    $( "#in_cost" ).prop( "disabled", true );

                    $( "#in_material_price" ).prop( "disabled", false );
                } else if (this.value == 'out'){
                    $( "#in_driver_id" ).prop( "disabled", false );
                    $( "#in_cost" ).prop( "disabled", false );

                    $( "#in_material_price" ).prop( "disabled", true );
                }
            });
        });
    </script>
@endpush
