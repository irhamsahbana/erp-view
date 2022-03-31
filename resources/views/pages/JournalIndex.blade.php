@extends('App')

@php
$dummyData = ['owner', 'kacab', 'kasir', 'material'];

$dummyData2 = [
[
'text' => 'Cabang A',
'value' => 'A'
],
[
'text' => 'Cabang B',
'value' => 'B'
],
[
'text' => 'Cabang C',
'value' => 'C'
],
[
'text' => 'Cabang D',
'value' => 'D'
],
];

$dummyData3 = [
[
'text' => 'Open',
'value' => 'open'
],
[
'text' => 'Close',
'value' => 'close'
],
];

$breadcrumbList = [
[
'name' => 'Home',
'href' => '/'
],
[
'name' => 'Jurnal'
],
];

$option = [
'value' => "test",
]
@endphp

@section('content-header', 'Jurnal')

@section('breadcrumb')
<x-breadcrumb :list="$breadcrumbList" />
@endsection

@section('content')
<x-content>
    <x-row>
        <x-card-collapsible :title="'Pencarian'" :collapse="false">
            <form style="width: 100%">
                <x-row>
                    <x-in-select :label="'Cabang'" :placeholder="'Pilih Cabang'" :col="6" :name="'branch_id'"
                        :options="$options['branches']" :value="app('request')->input('branch_id') ?? null"
                        :required="false"></x-in-select>
                    <x-in-text :type="'date'" :label="'Tanggal Mulai'" :col="6"
                    :value="app('request')->input('date_start') ?? null" :name="'date_start'"></x-in-text>
                    <x-in-select :label="'Kategori'" :placeholder="'Pilih Kategori'" :col="6" :name="'category_id'"
                        :required="false" :options="$options['categories']"></x-in-select>
                    <x-in-text :type="'date'" :label="'Tanggal Selesai'" :col="6"
                        :value="app('request')->input('date_finish') ?? null" :name="'date_finish'"></x-in-text>
                    <x-col class="text-right">
                        <a type="button" class="btn btn-default" href="{{ route('journal.index') }}">reset</a>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </x-col>
                </x-row>
            </form>
        </x-card-collapsible>

        <x-card-collapsible>
            <x-row>
                <x-col class="mb-3">
                    <a href="{{ route('add.journal') }}" class="btn btn-primary">Tambah</a>
                </x-col>
               
                <x-col class="mb-3">
                    @if (session('success'))
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                </x-col>
                <x-col>
                    <x-table :thead="['Tanggal', 'Cabang', 'Kategori', 'Referensi', 'Catatan', 'Status', 'Aksi']">

                            @foreach ($datas as $journal)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $journal->date }}</td>
                                <td>{{ $journal->branch->name }}</td>
                                <td>{{ $journal->category->label }}</td>
                                <td>{{ $journal->ref_no }}</td>
                                <td>{{ $journal->notes }}</td>
                                <td>
                                    @if ( $journal->is_open == 0)
                                    <button class="badge bg-danger border-0">Nonaktif</button>
                                    @else
                                    <button class="badge bg-success border-0">Aktif</button>
                                    @endif
                                </td>
                                <td nowrap="nowrap">
                                    <a href="{{ route('edit.journal', ['journal' => $journal->id]) }}"
                                        class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('delete.journal', $journal->id) }}"
                                        onclick="return confirm('Apakah anda yakin ?')" class="btn btn-danger"><i
                                            class="fas fa-trash"></i></a>
                                    <a href="{{ route('detail.journal', ['journal' => $journal->id]) }}"
                                        class="btn btn-primary"><i class="fas fa-stream"></i></a>
                                    {{-- <a class="btn btn-success"><i class="fas fa-edit"></i></a> --}}
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
            <x-in-select :label="'Cabang'" :placeholder="'Pilih Cabang'" :col="4" :id="'in_branch_id'"
                :name="'cabang_id'" :options="$option" :value="old('cabang_id')" :required="true"></x-in-select>
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