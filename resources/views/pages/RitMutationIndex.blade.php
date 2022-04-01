@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Mutasi Hutang Ritase'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Mutasi Hutang Ritase')

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
                            :col="4"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Proyek'"
                            :placeholder="'Pilih Proyek'"
                            :col="4"
                            :name="'project_id'"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Pengendara'"
                            :placeholder="'Pilih Pengendara'"
                            :col="4"
                            :name="'driver_id'"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Mutasi Material'"
                            :placeholder="'Pilih Mutasi Material'"
                            :col="4"
                            :name="'material_mutation_id'"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Status'"
                            :placeholder="'Pilih Status'"
                            :col="4"
                            :name="'is_open'"
                            :options="$options['status']"
                            :value="app('request')->input('is_open') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Jenis Transaksi'"
                            :placeholder="'Pilih Jenis Transaksi'"
                            :col="4"
                            :name="'transaction_type'"
                            :options="$options['transactionTypes']"
                            :value="app('request')->input('transaction_type') ? app('request')->input('transaction_type') : ''"
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
                            <a type="button" class="btn btn-default" href="{{ route('rit-mutation.index') }}">reset</a>
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
                        <x-table :thead="['Tanggal', 'Ref', 'Ref Mutasi Material', 'Cabang', 'Proyek', 'Pengendara', 'Jumlah (Biaya)', 'Status', 'Status Bayar', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->created }}</td>
                                    <td>{{ $data->ref_no }}</td>
                                    <td>{{ $data->materialMutation->ref_no }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->project->name }}</td>
                                    <td>{{ $data->driver->name }}</td>
                                    <td>{{ 'Rp. ' . number_format($data->amount, 2) }}</td>
                                    <td>
                                        @if($data->is_open)
                                            <span class="badge badge-success">Open</span>
                                        @else
                                            <span class="badge badge-danger">Close</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('rit-mutation.change-status-paid', $data->id) }}" style="display:inline!important;" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button
                                                type="submit"
                                                class="btn btn-{{ $data->is_paid == false ? 'danger' : 'success' }}"
                                                onclick="return confirm('Apakah anda ingin mengubah status pembayaran ini?')"
                                                title="ubah status"><i class="{{ $data->is_paid == false ? 'fas fa-times-circle' : 'fas fa-check-circle' }}"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        @if ($data->is_open)
                                            {{-- <a
                                                href="{{ route('rit-mutation.show', $data->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a> --}}
                                            <form
                                                style=" display:inline!important;"
                                                method="POST"
                                                action="{{ route('rit-mutation.destroy', $data->id) }}">
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
                                                action="{{ route('rit-mutation.change-status', $data->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                <button
                                                    type="submit"
                                                    class="btn btn-secondary"
                                                    onclick="return confirm('Apakah anda yakin ingin mengubah staus data ini?')"
                                                    title="Ubah"><i class="fas fa-sync-alt"></i></button>
                                            </form>
                                        @endif
                                        {{-- <a
                                            href="{{ route('rit-mutation.print', $data->id) }}"
                                            class="btn btn-info"
                                            title="Print"><i class="fas fa-file-alt"></i></a> --}}
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
        <form style="width: 100%" action="{{ route('rit-mutation.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                    :label="'Cabang'"
                    :placeholder="'Pilih Cabang'"
                    :col="4"
                    :id="'in_branch_id'"
                    :name="'branch_id'"
                    :options="$options['branches']"
                    :value="old('branch_id')"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Proyek'"
                    :placeholder="'Pilih Proyek'"
                    :col="4"
                    :id="'in_project_id'"
                    :name="'project_id'"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Pengendara'"
                    :placeholder="'Pilih Pengendara'"
                    :col="4"
                    :id="'in_driver_id'"
                    :name="'driver_id'"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Mutasi Material'"
                    :placeholder="'Pilih Mutasi Material'"
                    :col="6"
                    :id="'in_material_mutation_id'"
                    :name="'material_mutation_id'"
                    :required="true"></x-in-select>
                {{-- <x-in-select
                    :label="'Jenis Transaksi'"
                    :placeholder="'Pilih Jenis Transaksi'"
                    :col="6"
                    :name="'transaction_type'"
                    :options="$options['transactionTypes']"
                    :value="old('transaction_type')"
                    :required="true"></x-in-select> --}}
                <x-in-text
                    :type="'number'"
                    :step="0.01"
                    :label="'Jumlah'"
                    :col="6"
                    :value="old('amount')"
                    :name="'amount'"
                    :required="true"></x-in-text>
                {{-- <x-in-text
                    :type="'date'"
                    :label="'Tanggal'"
                    :col="6"
                    :value="old('created')"
                    :name="'created'"
                    :required="true"></x-in-text> --}}
                <x-in-text
                    :label="'Catatan'"
                    :value="old('notes')"
                    :required="true"
                    :name="'notes'"></x-in-text>
                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>
    {{-- <x-modal :title="'Tambah Data'" :id="'status-modal'">
        <form style="width: 100%" action="{{ route('rit-mutation.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                    :label="'Cabang'"
                    :placeholder="'Pilih Cabang'"
                    :col="4"
                    :id="'in_branch_id'"
                    :name="'branch_id'"
                    :options="$options['branches']"
                    :value="old('branch_id')"
                    :required="true"></x-in-select>
                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal> --}}
@endsection

@push('js')
    <!-- Select2 -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-branch" content="{{ Auth::user()->branch_id ?? null }}">

    <meta name="search-branch" content="{{ app('request')->input('branch_id') ?? null }}">
    <meta name="search-driver" content="{{ app('request')->input('driver_id') ?? null }}">
    <meta name="search-material-mutation" content="{{ app('request')->input('material_mutation') ?? null }}">
    <meta name="search-date-start" content="{{ app('request')->input('date_start') ?? null }}">
    <meta name="search-date-finish" content="{{ app('request')->input('date_finish') ?? null }}">

    <meta name="old-branch" content="{{ old('branch_id') ?? null }}">
    <meta name="old-driver" content="{{ old('driver_id') ?? null }}">
    <meta name="old-material-mutation" content="{{ old('material_mutation') ?? null }}">
    <meta name="old-amount" content="{{ old('amount') ?? null }}">
    <meta name="old-created" content="{{ old('created') ?? null }}">
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
