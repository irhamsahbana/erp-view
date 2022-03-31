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

@push('css')
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Jurnal')

@section('breadcrumb')
<x-breadcrumb :list="$breadcrumbList" />
@endsection

@section('content')
<x-content>
    <x-row>
        <x-card-collapsible :title="'Tambah Journal'" :collapse="false">
            <x-row>
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
                    <textarea class="form-control" name="" id="" cols="30" rows="5"
                        readonly>{{ $journal->notes }}</textarea>
                </div>
            </x-row>
            <div class="mt-3"></div>

            <x-row>
                <x-col>
                    <div class="mb-3">
                        <div class="my-2">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#add-modal">Tambah</button>
                        </div>
                    </div>
                </x-col>
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
                                <th>Proyek</th>
                                <th>Kelompok MA</th>
                                <th>MA</th>
                                <th>Sub MA</th>
                                <th>Catatan</th>
                                <th>Normal</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subJournal as $sub)

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sub->project->name }}</td>
                                <td>{{ $sub->budgetItemGroup->name }}</td>
                                <td>{{ $sub->budgetItem->name }}</td>
                                <td>{{ $sub->subBudgetItem->name }}</td>
                                <td>{{ $journal->notes }}</td>
                                <td>{{ $sub->category->label }}</td>
                                <td>
                                    @if ($sub->category->label == 'Kredit')
                                    {{ $sub->amount*-1 }}
                                    @else
                                    {{ $sub->amount }}
                                    @endif

                                </td>
                                <td>
                                    {{-- <a href="" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a> --}}
                                    <a href="{{ route('delete-sub-journal', ['sub_id' => $sub->id, 'journal_id' => $journal->id]) }}"
                                        class="btn btn-danger" onclick="return confirm('apakah anda yakin ?')"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="7">
                                    <h4>Total</h4>
                                </td>
                                <td>{{ $totalSub }}</td>
                                <td>

                                </td>
                            </tr>
                        </tbody>
                </div>
                </table>
            </x-row>
        </x-card-collapsible>
        {{-- Modal Add --}}
        <div class="modal fade" id="add-modal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Sub Jurnal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('post-sub-journal') }}" style="width: 100%" id="add-form-sub-journal"
                            method="post">
                            @csrf
                            @method('post')
                            <input type="hidden" name="journal_id" value="{{ $journal->id }}">
                            <div class="row">
                                <div class="col-sm-4 mb-2">
                                    <label for="">Kelompok Mata Anggaran</label>
                                    <select id="select_budget_item_group" name="budget_item_group_id"
                                        class="form-control" required>
                                        <option value="">Pilih Kelompok Mata Anggaran</option>
                                        @foreach ($budgetItemGroups as $big)
                                        <option value="{{ $big->id }}">{{ $big->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <label for="">Mata Anggaran</label>
                                    <select id="select_budget_item" name="budget_item_id" class="form-control" required>
                                        <option value="">Pilih Kelompok MA</option>
                                    </select>

                                </div>
                                <div class="col-sm-4 mb-2">
                                    <label for="">Mata Anggaran</label>
                                    <select id="select_sub_budget_item" name="sub_budget_item_id" class="form-control"
                                        required>
                                        <option value="">Pilih Sub Mata Anggaran</option>
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <label for="">Proyek</label>
                                    <select id="project" name="project_id" class="form-control" required>
                                        <option value="">Pilih Proyek</option>
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <label for="">Saldo Normal</label>
                                    <select id="normal_balance" name="normal_balance_id" class="form-control" required>
                                        <option value="">Pilih Saldo Normal</option>
                                        @foreach ($balances as $nb)
                                        <option value="{{ $nb->id }}">{{ $nb->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <label for="">Jumlah</label>
                                    <input class="form-control" type="number" name="amount" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </x-row>
</x-content>
@endsection
@push('js')
<!-- Select2 -->
<script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script src="{{ asset('assets') }}/dist/js/collect.js"></script>
{{-- <script src="{{ asset('assets') }}/dist/js/undescore.js"></script> --}}

<meta name="url-branch" content="{{ route('branch.index') }}">
<meta name="url-project" content="{{ route('project.index') }}">
<meta name="url-vendor" content="{{ route('vendor.index') }}">

<meta name="url-budget-item" content="{{ route('get-budget-item') }}">
<meta name="url-sub-budget-item" content="{{ route('get-sub-budget-item') }}">
<meta name="url-project" content="{{ route('project.index') }}">
<meta name="url-normal-balances" content="{{ route('get-normal-balance') }}">

<meta name='branch-id' content="{{ $journal->id }}">
<meta name="journal-note" content="{{ $journal->notes }}">
<meta name="url-budget-item-group" content="{{ route('get-budget-item-group') }}">

<script>
    let journalDetail = [];

    const project = [];
    const budgetItemGroups = [];
    const budgetItems = [];
    const subBudgetItems = [];
    const normalBalances = [];

    const deleteJournalDetailIds = [];

    function deleteJournalDetail(hash) {
        let newJD = collect(journalDetail).filter((value, key) => value.hash != hash);

        journalDetail = newJD.all();

        $('tbody').empty();

        journalDetail.forEach(function (params, index) {
            let dataProject = collect(project).first(item => item.id == params.project_id);
            let dataBudgetItemGroup = collect(budgetItemGroups).first(item => item.id == params.budget_item_group_id);
            let dataBudgetItem = collect(budgetItems).first(item => item.id == params.budget_item_id);
            let dataSubBudgetItem = collect(subBudgetItems).first(item => item.id == params.sub_budget_item_id);
            let dataNormalBalance = collect(normalBalances).first(item => item.id == params.normal_balance_id);

            $('#table-sub-journal > tbody:last-child').append(`
                <tr id="sub-jurnal-${journalDetail.hash}">
                    <td>${ parseInt(index) + 1 }</td>
                    <td>${dataProject.name}</td>
                    <td>${dataBudgetItemGroup.name}</td>
                    <td>${dataBudgetItem.name}</td>
                    <td>${dataSubBudgetItem.name}</td>
                    <td>${journalNote}</td>
                    <td>${dataNormalBalance.label}</td>
                    <td>${params.amount}</td>
                    <td>
                        <button onclick="deleteJournalDetail(${params.hash})" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        });
        
        console.log(journalDetail, 'dari func delete');
    }

    $(document).ready(function() {

        // Get All Project
        $.ajax({
            url: $('meta[name="url-project"]').attr('content'),
            data: {
                id: "",
            },
            cache: true,
            success: function (data) {
                data.datas.forEach(function (element) {
                    project.push(element)
                });
            }
        });

        // Get All Budget Item Group
        $.ajax({
            url: $('meta[name="url-budget-item-group"]').attr('content'),
            data: {
                id: "",
            },
            cache: true,
            success: function (data) {
                data.forEach(function (element) {
                    budgetItemGroups.push(element)
                });
            }
        });

         // Get All Budget Item
        $.ajax({
            url: $('meta[name="url-budget-item"]').attr('content'),
            data: {
                id: "",
            },
            cache: true,
            success: function (data) {
                data.forEach(function (element) {
                    budgetItems.push(element)
                });
            }
        });

        // Get All Sub Budget Item
        $.ajax({
            url: $('meta[name="url-sub-budget-item"]').attr('content'),
            data: {
                id: "",
            },
            cache: true,
            success: function (data) {
                data.forEach(function (element) {
                    subBudgetItems.push(element)
                });
            }
        });

        // Get All Normal Balance
        $.ajax({
            url: $('meta[name="url-normal-balances"]').attr('content'),
            data: {
                id: "",
            },
            cache: true,
            success: function (data) {
                data.forEach(function (element) {
                    normalBalances.push(element)
                });
            }
        });

        $('#add-sub-journal').on('click', function (){
            let data = $("#add-form-sub-journal").serialize().split("&");

            
            let obj = {};
            let journalNote = $('meta[name="journal-note"]').attr('content');

            for(let key in data)
                obj[data[key].split("=")[0]] = data[key].split("=")[1];

            obj.hash = Math.floor(Math.random() * 999999999) + 10000000;

            journalDetail.push(obj);

            $('tbody').empty();

            journalDetail.forEach(function (params, index) {

                let dataProject = collect(project).first(item => item.id == params.project_id);
                let dataBudgetItemGroup = collect(budgetItemGroups).first(item => item.id == params.budget_item_group_id);
                let dataBudgetItem = collect(budgetItems).first(item => item.id == params.budget_item_id);
                let dataSubBudgetItem = collect(subBudgetItems).first(item => item.id == params.sub_budget_item_id);
                let dataNormalBalance = collect(normalBalances).first(item => item.id == params.normal_balance_id);

                $('#table-sub-journal > tbody:last-child').append(`
                    <tr id="sub-jurnal-${journalDetail.hash}">
                        <td>${ parseInt(index) + 1 }</td>
                        <td>${dataProject.name}</td>
                        <td>${dataBudgetItemGroup.name}</td>
                        <td>${dataBudgetItem.name}</td>
                        <td>${dataSubBudgetItem.name}</td>
                        <td>${journalNote}</td>
                        <td>${dataNormalBalance.label}</td>
                        <td>${params.amount}</td>
                        <td>
                            <button onclick="deleteJournalDetail(${params.hash})" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `);
            });

            $('#add-modal').modal('toggle');
        });

        //Get project for select option
        $.ajax({
            url: $('meta[name="url-project"]').attr('content'),
            data: {
                id: $('meta[name="branch-id"]').attr('content'),
            },
            cache: false,
            success: function (data) {
                $('#project').empty();
                $('#project').append(`<option value="">Pilih Proyek</option>`);

                data.datas.forEach(function (element) {
                    let option = `<option value="${element.id}">${element.name}</option>`;

                    $('#project').append(option);
                });

                $('#project').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Pilih Proyek',
                    allowClear: true
                });
            }
        });

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