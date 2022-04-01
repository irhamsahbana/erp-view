@extends('App')

@php
$breadcrumbList = [
[
'name' => 'Home',
'href' => '/'
],
[
'name' => 'Buku Besar'
],
];

$option = [
'value' => "test",
]
@endphp

@push('css')
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Buku Besar')

@section('breadcrumb')
<x-breadcrumb :list="$breadcrumbList" />
@endsection

@section('content')
<x-content>
    <x-row>
        <x-card-collapsible :title="'Filter Sub Jurnal'" :collapse="false">
            <form style="width: 100%" method="GET" action="">
                <x-row>
                    <div class="col-sm-12">
                        <label for="">Cabang</label>
                        <select class="form-control" name="branch_id" id="select_branch">
                            <option value="">Pilih Cabang</option>
                            @foreach ($options['branches'] as $br)
                            <option value="{{ $br['value'] }}">{{ $br['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </x-row>
                <div class="my-2"></div>
                <x-row>
                    <div class="col-sm-3">
                        <label for="">Proyek</label>
                        <select class="form-control" name="project_id" id="select_project">
                            <option value="">Pilih Proyek</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Kelompok Mata Anggaran</label>
                        <select class="form-control" name="budget_item_group_id" id="select_budget_item_group">
                            <option value="">Pilih Kelompok Mata Anggaran</option>
                            @foreach ($budgetItemGroup as $big)
                            <option value="{{ $big->id }}">{{ $big->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Mata Anggaran</label>
                        <select class="form-control" name="budget_item_id" id="select_budget_item">
                            <option value="">Pilih Mata Anggaran</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Sub Mata Anggaran</label>
                        <select class="form-control" name="sub_budget_item_id" id="select_sub_budget_item">
                            <option value="">Pilih Sub Mata Anggaran</option>
                        </select>
                    </div>
                </x-row>
                <div class="my-2"></div>
                <x-row>
                    <div class="col-sm-6">
                        <label for="">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="date_start">
                    </div>
                    <div class="col-sm-6">
                        <label for="">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="date_finish">
                    </div>
                </x-row>
                <div class="my-2"></div>
                <x-col class="text-right">
                    <a type="button" class="btn btn-default" href="{{ route('general.ledger.index') }}">reset</a>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </x-col>
            </form>
        </x-card-collapsible>
        <x-card-collapsible :title="'Sub Jurnal'" :collapse="false">
            <x-row>
                <x-col>
                    @if (session('success'))
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        {{session('success') }}
                        <button wire:click='resetData' type="button" class="close" data-dismiss="alert"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                </x-col>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="table-sub-journal">
                        <thead>
                            <tr>
                                <th style="width: 10px;">#</th>
                                <th>Tanggal</th>
                                <th>No. Jurnal</th>
                                <th>Proyek</th>
                                <th>Kelompok MA</th>
                                <th>MA</th>
                                <th>Sub MA</th>
                                <th>Catatan</th>
                                <th>Posisi</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="9">
                                    <h5>Saldo Awal</h5>
                                </td>
                                <td>{{ $firstSaldo }}</td>
                            </tr>
                            @foreach ($subJournal as $sub)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sub->created }}</td>
                                <td>{{ $sub->ref_no }}</td>
                                <td>{{ $sub->project_name }}</td>
                                <td>{{ $sub->budget_item_group_name }}</td>
                                <td>{{ $sub->budget_item_name }}</td>
                                <td>{{ $sub->sub_budget_item_name }}</td>
                                <td>{{ $sub->notes }}</td>
                                <td>{{ $sub->category_name }}</td>
                                <td>{{ $sub->amount }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="9">
                                    <h5>Saldo Akhir</h5>
                                </td>
                                <td>{{ $lastSaldo }}</td>
                            </tr>
                        </tbody>
                </div>
                </table>
            </x-row>
        </x-card-collapsible>
    </x-row>
</x-content>
@endsection
@push('js')
<!-- Select2 -->
<script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script src="{{ asset('assets') }}/dist/js/collect.js"></script>
{{-- <script src="{{ asset('assets') }}/dist/js/undescore.js"></script> --}}
<meta name="search-branch" content="{{ app('request')->input('branch_id') ?? null }}">
<meta name="search-project" content="{{ app('request')->input('project_id') ?? null }}">

<meta name="url-branch" content="{{ route('branch.index') }}">
<meta name="url-project" content="{{ route('project.index') }}">
<meta name="url-vendor" content="{{ route('vendor.index') }}">

<meta name="url-budget-item" content="{{ route('get-budget-item') }}">
<meta name="url-sub-budget-item" content="{{ route('get-sub-budget-item') }}">
<meta name="url-project" content="{{ route('project.index') }}">
<meta name="url-normal-balances" content="{{ route('get-normal-balance') }}">

{{--
<meta name='branch-id' content="{{ $journal->id }}">
<meta name="journal-note" content="{{ $journal->notes }}"> --}}
<meta name="url-budget-item-group" content="{{ route('get-budget-item-group') }}">
<script>
    $(document).ready(function() {
        let selectBranch = $('#select_branch');
        let selectProject = $('#select_project');

        selectBranch.on('change', function () {
            let branchId = $(this).val();
            let searchProject = $('meta[name="search-project"]').attr('content');

            if (branchId == '') {
                selectProject.empty();
                selectProject.append('<option value="">Pilih Proyek</option>');

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
        });

        if (selectBranch.val() != '')
            selectBranch.trigger('change');

        $('#select_budget_item_group').on('change', function(){
            let budgetItemGroupId = $(this).val();
            let url = $('meta[name="url-budget-item"]').attr('content');

            if(budgetItemGroupId == "")
                return;

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    id: budgetItemGroupId
                },
                cache: false,
                success: function (data) {
                    $('#select_budget_item').empty();
                    $('#select_budget_item').append(`<option value="">Pilih Mata Anggaran</option>`);

                    $('#select_sub_budget_item').empty();
                    $('#select_sub_budget_item').append(`<option value="">Pilih Sub Mata Anggaran</option>`);

                    data.forEach(element => {
                        let option = `<option value="${element.id}">${element.name}</option>`;

                        $('#select_budget_item').append(option);
                    });
                }
            });
        });

        $('#select_budget_item').on('change', function() {
            let budgetItemId = $(this).val();
            let url = $('meta[name="url-sub-budget-item"]').attr('content');
            
            if(budgetItemId == "")
                return;

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    id: budgetItemId
                },
                cache: false,
                success: function (data) {

                    $('#select_sub_budget_item').empty();
                    $('#select_sub_budget_item').append(`<option value="">Pilih Sub Mata Anggaran</option>`);

                    data.forEach(element => {
                        let option = `<option value="${element.id}">${element.name}</option>`;

                        $('#select_sub_budget_item').append(option);
                    });
                }
            });
        });
    });
</script>
@endpush