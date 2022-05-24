@extends('App')

@php
$breadcrumbList = [
[
'name' => 'Home',
'href' => '/'
],
[
'name' => 'Mutasi Hutang'
],
];
@endphp

@push('css')
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Mutasi Hutang')

@section('breadcrumb')
<x-breadcrumb :list="$breadcrumbList" />
@endsection

@section('content')
<x-content>
    <x-row>
        <x-card-collapsible :title="'Pencarian'" :collapse="true">
            <form style="width: 100%">
                <x-row>
                    <x-in-select :label="'Cabang'" :placeholder="'Pilih Cabang'" :col="4" :name="'branch_id'"
                        :options="$options['branches']" :value="app('request')->input('branch_id') ?? null"
                        :required="false"></x-in-select>
                    <x-in-select :label="'Proyek'" :placeholder="'Pilih Proyek'" :col="4" :name="'project_id'"
                        :required="false"></x-in-select>
                    <x-in-select :label="'Vendor'" :placeholder="'Pilih Vendor'" :col="4" :name="'vendor_id'"
                        :required="false"></x-in-select>
                    <x-in-select :label="'Status'" :placeholder="'Pilih Status'" :col="4" :name="'is_open'"
                        :options="$options['status']" :value="app('request')->input('is_open') ?? null"
                        :required="false"></x-in-select>
                    <x-in-select :label="'Jenis Mutasi'" :placeholder="'Pilih Jenis Mutasi'" :col="4" :name="'type'"
                        :options="$options['types']"
                        :value="app('request')->input('type') ? app('request')->input('type') : ''" :required="false">
                    </x-in-select>
                    <x-in-select :label="'Jenis Transaksi'" :placeholder="'Pilih Jenis Transaksi'" :col="4"
                        :name="'transaction_type'" :options="$options['transactionTypes']"
                        :value="app('request')->input('transaction_type') ? app('request')->input('transaction_type') : ''"
                        :required="false"></x-in-select>
                    <x-in-text :type="'date'" :label="'Tanggal Mulai'" :col="6"
                        :value="app('request')->input('date_start') ?? null" :name="'date_start'"></x-in-text>
                    <x-in-text :type="'date'" :label="'Tanggal Selesai'" :col="6"
                        :value="app('request')->input('date_finish') ?? null" :name="'date_finish'"></x-in-text>
                    <x-col class="text-right">
                        <a type="button" class="btn btn-default" href="{{ route('debt-mutation.index') }}">reset</a>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </x-col>
                </x-row>
            </form>
        </x-card-collapsible>

        <x-card-collapsible>
            <x-row>
                <x-col class="mb-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#add-modal">Tambah</button>
                </x-col>

                <x-col>
                    <x-table
                        :thead="['Tanggal', 'Ref', 'Cabang', 'proyek', 'Vendor', 'Jenis', 'Jenis Transaksi', 'Jumlah',  'Aksi']">
                        @foreach($datas as $data)

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->created }}</td>
                            <td>{{ $data->ref_no }}</td>
                            <td>{{ $data->branch->name }}</td>
                            <td>{{ $data->project->name }}</td>
                            <td>{{ $data->vendor->name }}</td>
                            <td>{{ $data->debtType->label }}</td>
                            <td>
                                @if($data->transaction_type == 1)
                                Tambah
                                @else
                                Kurang
                                @endif
                            </td>
                            <td class="text-right">{{  number_format($data->amount) }}</td>

                            <td>
                                @if ($data->is_open)
                                <a href="{{ route('debt-mutation.show', $data->id) }}" class="btn btn-warning"
                                    title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                <form style=" display:inline!important;" method="POST"
                                    action="{{ route('debt-mutation.destroy', $data->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                        title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                @endif
                                @if(Auth::user()->role == 'owner')
                                <form style=" display:inline!important;" method="POST"
                                    action="{{ route('debt-mutation.change-status', $data->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" class="btn btn-secondary"
                                        onclick="return confirm('Apakah anda yakin ingin mengubah staus data ini?')"
                                        title="Ubah"><i class="fas fa-sync-alt"></i></button>
                                </form>
                                @endif
                                <a href="{{ route('debt-mutation.print', $data->id) }}" class="btn btn-info"
                                    title="Print"><i class="fas fa-file-alt"></i></a>
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
    <form style="width: 100%" action="{{ route('debt-mutation.store') }}" method="POST">
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
            <x-in-select :label="'Vendor'" :placeholder="'Pilih Vendor'" :col="4" :id="'in_vendor_id'"
                :name="'vendor_id'" :required="true"></x-in-select>
            <x-in-select :label="'Jenis Mutasi'" :placeholder="'Pilih Jenis Mutasi'" :col="6" :name="'type'"
                :options="$options['types']" :value="old('type')" :required="true"></x-in-select>
            <x-in-select :label="'Jenis Transaksi'" :placeholder="'Pilih Jenis Transaksi'" :col="6"
                :name="'transaction_type'" :options="$options['transactionTypes']" :value="old('transaction_type')"
                :required="true"></x-in-select>
            <x-in-text :type="'number'" :step="0.01" :label="'Jumlah'" :col="6" :value="old('amount')" :name="'amount'"
                :required="true"></x-in-text>
            <x-in-text :type="'date'" :label="'Tanggal'" :col="6" :value="old('created')" :name="'created'"
                :required="true"></x-in-text>
            <x-in-text :label="'Catatan'" :value="old('notes')" :required="true" :name="'notes'"></x-in-text>
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

<meta name="search-branch" content="{0{ app('request')->input('branch_id') ?? null }}">
<meta name="search-project" content="{{ app('request')->input('project_id') ?? null }}">
<meta name="search-vendor" content="{{ app('request')->input('vendor_id') ?? null }}">
<meta name="search-date-start" content="{{ app('request')->input('date_start') ?? null }}">
<meta name="search-date-finish" content="{{ app('request')->input('date_finish') ?? null }}">
<meta name="search-status" content="{{ app('request')->input('status') ?? null }}">

<meta name="old-branch" content="{{ old('branch_id') ?? null }}">
<meta name="old-project" content="{{ old('project_id') ?? null }}">
<meta name="old-vendor" content="{{ old('vendor_id') ?? null }}">
<meta name="old-amount" content="{{ old('amount') ?? null }}">
<meta name="old-create" content="{{ old('created') ?? null }}">
<meta name="old-status" content="{{ old('is_open') ?? null }}">

<meta name="url-branch" content="{{ route('branch.index') }}">
<meta name="url-project" content="{{ route('project.index') }}">
<meta name="url-vendor" content="{{ route('vendor.index') }}">

<meta name="">

{{-- Searching --}}
<script>
    $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            let selectVendor = $('#vendor_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let searchProject = $('meta[name="search-project"]').attr('content');
                let searchVendor = $('meta[name="search-vendor"]').attr('content');

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

                        if (searchProject != '') {
                            selectProject.val(searchProject).trigger('change');
                        }
                    }
                });

                // Get vendor
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

                        if (searchVendor != '') {
                            selectVendor.val(searchVendor).trigger('change');
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
            let selectVendorIn = $('#in_vendor_id');

            selectBranchIn.on('change', function () {
                let branchId = $(this).val();
                let searchProject = $('meta[name="search-project"]').attr('content');
                let searchVendor = $('meta[name="search-vendor"]').attr('content');

                if (branchId == '') {
                    selectProjectIn.empty();
                    selectProjectIn.append('<option value="">Pilih Proyek</option>');

                    selectVendorIn.empty();
                    selectVendorIn.append('<option value="">Pilih Vendor</option>');

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

                // Get vendor
                $.ajax({
                    url: $('meta[name="url-vendor"]').attr('content'),
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                    },
                    success: function (data) {
                        let oldVendor = $('meta[name="old-vendor"]').attr('content');

                        selectVendorIn.empty();
                        selectVendorIn.append(`<option value="">Pilih Vendor</option>`);

                        data.datas.forEach(function(item) {
                            selectVendorIn.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectVendorIn.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Vendor',
                            allowClear: true,
                        });

                        if (oldVendor != '') {
                            selectVendorIn.val(oldVendor).trigger('change');
                        }
                    }
                });
            });

            if (selectBranchIn.val() != '')
                selectBranchIn.trigger('change');
        });
</script>
@endpush
