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
        <x-card-collapsible :title="'Tambah Journal'" :collapse="false">
            <form action="{{ route('save.journal') }}" method="POST">
                @csrf
                @method('post')
                {{-- <x-row> --}}
                    {{-- <x-in-select :name="'branch_id'" :required="'required'" :placeholder="'Pilih Cabang'" :option="{{ $branch }}">

                    </x-in-select> --}}
                    <div class="my-2">
                        {{-- <label for="">Pilih Cabang</label> --}}
                        <select class="form-control" name="branch_id" required>
                            <option value="">Pilih Cabang</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="my-2">
                        <label for=""></label>
                        <select class="form-control" name="journal_category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="my-2">
                        <label for=""></label>
                        <input class="form-control" type="date" name="date" placeholder="Tanggal" required>
                    </div>
                    {{-- <div class="my-2">
                        <label for=""></label>
                        <input class="form-control" type="text" name="voucher_number" placeholder="Voucher Number" required>
                    </div> --}}
                    <div class="my-2">
                        <label for=""></label>
                        {{-- <input class="form-control" type="text" name="voucher_number" placeholder="Voucher Number" required> --}}
                        <textarea name="notes" class="form-control" cols="30" rows="10" required></textarea>
                    </div>
                    {{-- <x-text :id="'l'" :type="'text'" :placeholder="''" :name="">

                    </x-text> --}}
                {{-- </x-row> --}}
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
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