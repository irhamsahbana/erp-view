@extends('App')

@php
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
            <div class="row">
                <div class="col-sm-4 mb-2">
                    <input class="form-control" type="text" value="{{ $journal->branch->name }}" readonly>
                </div>
                <div class="col-sm-4 mb-2">
                    <input class="form-control" type="text" value="{{ $journal->user->username }}" readonly>
                </div>
                <div class="col-sm-4 mb-2">
                    <input class="form-control" type="text" value="{{ $journal->category->label }}" readonly>
                </div>
                <div class="col-sm-4 mb-2">
                    <input class="form-control" type="text" value="{{ $journal->date }}" readonly>
                </div>
                <div class="col-sm-4 mb-2">
                    <input class="form-control" type="text" value="{{ $journal->voucher_number }}" readonly>
                </div>
                <div class="col-sm-4 mb-2">
                    <input class="form-control" type="text"
                        value="{{ ($journal->is_open == 0) ? 'Nonaktif' : 'Aktif' }}" readonly>
                </div>
            </div>
            <textarea class="form-control" name="" id="" cols="30" rows="5" readonly>{{ $journal->notes }}</textarea>
            <livewire:journal.sub-journal :subjournal='$subjournals' :journal='$journal'>
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
@endpush