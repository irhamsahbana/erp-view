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
                    <x-in-text
                        :type="'text'"
                        :label="'Cabang'"
                        :col="4"
                        :readonly="'true'"
                        :value="$journal->branch->name">
                    </x-in-text>

                    <x-in-text
                        :type="'text'"
                        :label="'Dibuat Oleh'"
                        :col="4"
                        :readonly="'true'"
                        :value="$journal->user->username">
                    </x-in-text>

                    <x-in-text
                        :type="'text'"
                        :label="'Posisi'"
                        :col="4"
                        :readonly="'true'"
                        :value="$journal->category->label">
                    </x-in-text>

                    <x-in-text
                        :type="'text'"
                        :label="'Tanggal'"
                        :col="4"
                        :readonly="'true'"
                        :value="$journal->created">
                    </x-in-text>

                    <x-in-text
                        :type="'text'"
                        :label="'Nomor Referensi'"
                        :col="4"
                        :readonly="'true'"
                        :value="$journal->ref_no">
                    </x-in-text>

                    <x-in-text
                        :type="'text'"
                        :label="'Status'"
                        :col="4"
                        :readonly="'true'"
                        :value="($journal->is_open == 0) ? 'Nonaktif' : 'Aktif'">
                    </x-in-text>

                    <div class="col-sm-12">
                        <label for="">Catatan</label>
                        <textarea class="form-control" name="" id="" cols="30" rows="5"
                            readonly>{{ $journal->notes }}</textarea>
                    </div>
                </div>
            </x-row>

            <x-row>
                <x-col>
                    <div class="my-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#add-modal">Tambah</button>
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
                    <x-table :thead="['Proyek', 'Kelompok MA', 'MA', 'Sub MA', 'Catatan', 'Posisi', 'Jumlah', 'Aksi']">
                        @foreach ($subJournal as $sub)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sub->project->name }}</td>
                            <td>{{ $sub->budgetItemGroup->name }}</td>
                            <td>{{ $sub->budgetItem->name }}</td>
                            <td>{{ $sub->subBudgetItem->name }}</td>
                            <td>{{ $journal->notes }}</td>
                            <td>{{ $sub->category->label }}</td>
                            <td class="text-right">{{ number_format($sub->amount) }}</td>
                            <td>
                                <button
                                    class="btn btn-warning btn-edit-sub-journal"
                                    data-toggle="modal"
                                    data-target="#edit-modal"
                                    data-sub-journal-id="{{ $sub->id }}"
                                    data-project-id="{{ $sub->project_id }}"
                                    data-budget-item-group-id="{{ $sub->budget_item_group_id }}"
                                    data-budget-item-id="{{ $sub->budget_item_id }}"
                                    data-sub-budget-item-id="{{ $sub->sub_budget_item_id }}"
                                    data-sub-category-id='{{ $sub->normal_balance_id }}'
                                    data-amount="{{ $sub->amount }}"><i class="fas fa-edit"></i>
                                </button>
                                <a
                                    href="{{ route('delete-sub-journal', ['sub_id' => $sub->id, 'journal_id' => $journal->id]) }}"
                                    class="btn btn-danger"
                                    onclick="return confirm('apakah anda yakin ?')">
                                    <i
                                        class="fas fa-trash">
                                    </i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="7">
                                <h5>Total Selisih Debit dan Kredit</h5>
                            </td>
                            <td class="text-right">
                                {{ number_format($totalSub) }}
                            </td>
                            <td>

                            </td>
                        </tr>
                    </x-table>
                </div>
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
                            <x-row>

                                <x-in-select
                                    :label="'Kelompok Mata Anggaran'"
                                    :placeholder="'Pilih Kelompok Mata Anggaran'"
                                    :id="'select_budget_item_group'"
                                    :name="'budget_item_group_id'"
                                    :options="$options['budgetItemGroups']"
                                    :required="true" :col="4">
                                </x-in-select>

                                <x-in-select
                                    :label="'Kelompok Mata Anggaran'"
                                    :placeholder="'Pilih Mata Anggaran'"
                                    :id="'select_budget_item'"
                                    :name="'budget_item_id'"
                                    :required="true"
                                    :col="4">
                                </x-in-select>

                                <x-in-select
                                    :label="'Kelompok Sub Mata Anggaran'"
                                    :placeholder="'Pilih Sub Mata Anggaran'"
                                    :id="'select_sub_budget_item'"
                                    :name="'sub_budget_item_id'"
                                    :required="true"
                                    :col="4">
                                </x-in-select>

                                <x-in-select
                                    :label="'Proyek'"
                                    :placeholder="'Pilih Proyek'"
                                    :id="'project'"
                                    :name="'project_id'"
                                    :required="true"
                                    :col="4">
                                </x-in-select>

                                <x-in-select
                                    :label="'Saldo Normal'"
                                    :placeholder="'Pilih Saldo Normal'"
                                    :id="'normal_balance'"
                                    :name="'normal_balance_id'"
                                    :required="true"
                                    :col="4">
                                </x-in-select>

                                <x-in-text
                                    :type="'text'"
                                    :label="'Jumlah'"
                                    :col="4"
                                    :name="'amount'"
                                    :required="true">
                                </x-in-text>

                            </x-row>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        {{-- Modal Edit --}}
        <div class="modal fade" id="edit-modal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Sub Jurnal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div id="formeditsubjournal">
                            <form action="{{ route('edit.sub.journal') }}" class="" style="width: 100%" method="post">
                                @csrf
                                @method('post')
                                <x-row>
                                    <input type="hidden" name="journal_id_edit" value="{{ $journal->id }}">
                                    <input type="hidden" name="id_sub_journal" id="field_input_id">
                                    <x-in-select
                                        :label="'Kelompok Mata Anggaran'"
                                        :placeholder="'Pilih Kelompok Mata Anggaran'"
                                        :id="'select_budget_item_group_edit'"
                                        :name="'budget_item_group_id'"
                                        :required="true"
                                        :col="4">
                                    </x-in-select>

                                    <x-in-select
                                        :label="'Kelompok Mata Anggaran'"
                                        :placeholder="'Pilih Mata Anggaran'"
                                        :id="'select_budget_item_edit'"
                                        :name="'budget_item_id'"
                                        :required="true"
                                        :col="4">
                                    </x-in-select>

                                    <x-in-select
                                        :label="'Kelompok Sub Mata Anggaran'"
                                        :placeholder="'Pilih Sub Mata Anggaran'"
                                        :id="'select_sub_budget_item_edit'"
                                        :name="'sub_budget_item_id'"
                                        :required="true"
                                        :col="4">
                                    </x-in-select>

                                    <x-in-select
                                        :label="'Proyek'"
                                        :placeholder="'Pilih Proyek'"
                                        :id="'project_edit'"
                                        :name="'project_id'"
                                        :required="true"
                                        :col="4">
                                    </x-in-select>

                                    <x-in-select
                                        :label="'Saldo Normal'"
                                        :placeholder="'Pilih Saldo Normal'"
                                        :id="'normal_balance_edit'"
                                        :name="'normal_balance_id'"
                                        :required="true"
                                        :col="4">
                                    </x-in-select>

                                    <x-in-text
                                        :type="'text'"
                                        :label="'Jumlah'"
                                        :col="4"
                                        :id="'amount_edit'"
                                        :name="'amount'"
                                        :required="true">
                                    </x-in-text>

                                </x-row>
                                <button type="submit" class="btn btn-primary float-right">Simpan</button>
                            </form>
                        </div>
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

<meta name='branch-id' content="{{ $journal->branch_id }}">
<meta name="journal-note" content="{{ $journal->notes }}">
<meta name="journal-id" content="{{ $journal->id }}">
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

        // Set Normal Balance To Select Element
        $.ajax({
            url: $('meta[name="url-normal-balances"]').attr('content'),
            data: {
                id: "",
            },
            cache: true,
            success: function (data) {
                data.forEach(function (element) {
                    // normalBalances.push(element)
                    let option = `<option value="${element.id}">${element.label}</option>`;

                    $('#normal_balance').append(option);
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

        // Get project for select option
        $.ajax({
            url: $('meta[name="url-project"]').attr('content'),
            data: {
                branch_id: $('meta[name="branch-id"]').attr('content'),
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

        // edit button sub journal click
        $('.btn-edit-sub-journal').on('click', function(){
            let subJournalId = $(this).attr('data-sub-journal-id')
            let budgetItemGroupId = $(this).attr('data-budget-item-group-id')
            let budgetItemId = $(this).attr('data-budget-item-id')
            let subBudgetItemId = $(this).attr('data-sub-budget-item-id')
            let subCategoryId = $(this).attr('data-sub-category-id')
            let projectId = $(this).attr('data-project-id')
            let amount = $(this).attr('data-amount')

            // get budget item group for edit
            $('#field_input_id').val(subJournalId)
            $.ajax({
                url: $('meta[name="url-budget-item-group"]').attr('content'),
                data: {
                    id: "",
                },
                cache: true,
                success: function (data) {
                    $('#select_budget_item_group_edit').empty();

                    data.forEach(function (element) {
                        let option = `<option value="${element.id}" ${element.id == budgetItemGroupId ? 'selected' : ''}>${element.name}</option>`;

                        $('#select_budget_item_group_edit').append(option);
                    });
                }
            });

            // Get Budget Item
            $.ajax({
                url: $('meta[name="url-budget-item"]').attr('content'),
                data: {
                    id: budgetItemGroupId,
                },
                cache: true,
                success: function (data) {

                    $('#select_budget_item_edit').empty();

                    data.forEach(element => {
                        let option = `<option value="${element.id}" ${element.id == budgetItemId ? 'selected' : ''}>${element.name}</option>`;

                        $('#select_budget_item_edit').append(option);
                    });
                }
            });

            // Get Sub Budget Item
            $.ajax({
                url: $('meta[name="url-sub-budget-item"]').attr('content'),
                data: {
                    id: budgetItemId,
                },
                cache: true,
                success: function (data) {
                    $('#select_sub_budget_item_edit').empty();

                    data.forEach(element => {
                        let option = `<option value="${element.id}" ${element.id == subBudgetItemId ? 'selected' : ''}>${element.name}</option>`;

                        $('#select_sub_budget_item_edit').append(option);
                    });
                }
            });

            // Select on change
            $('#select_budget_item_group_edit').on('change', function(){
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
                        $('#select_budget_item_edit').empty();
                        $('#select_budget_item_edit').append(`<option value="">Pilih Mata Anggaran</option>`);

                        $('#select_sub_budget_item_edit').empty();
                        $('#select_sub_budget_item_edit').append(`<option value="">Pilih Sub Mata Anggaran</option>`);

                        data.forEach(element => {
                            let option = `<option value="${element.id}">${element.name}</option>`;

                            $('#select_budget_item_edit').append(option);
                        });
                    }
                });
            });

            $('#select_budget_item_edit').on('change', function() {
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

                        $('#select_sub_budget_item_edit').empty();
                        $('#select_sub_budget_item_edit').append(`<option value="">Pilih Sub Mata Anggaran</option>`);

                        data.forEach(element => {
                            let option = `<option value="${element.id}">${element.name}</option>`;

                            $('#select_sub_budget_item_edit').append(option);
                        });
                    }
                });
            });

             // Get project for select option
            $.ajax({
                url: $('meta[name="url-project"]').attr('content'),
                data: {
                    branch_id: $('meta[name="branch-id"]').attr('content'),
                },
                cache: false,
                success: function (data) {
                    $('#project_edit').empty();
                    $('#project_edit').append(`<option value="">Pilih Proyek</option>`);

                    data.datas.forEach(function (element) {
                        let option = `<option value="${element.id}" ${element.id == projectId ? 'selected' : ''}>${element.name}</option>`;

                        $('#project_edit').append(option);
                    });

                    $('#project_edit').select2({
                        theme: 'bootstrap4',
                        placeholder: 'Pilih Proyek',
                        allowClear: true
                    });
                }
            });

             // Set Normal Balance To Select Element
            $.ajax({
                url: $('meta[name="url-normal-balances"]').attr('content'),
                data: {
                    id: "",
                },
                cache: false,
                success: function (data) {
                    $('#normal_balance_edit').empty();
                    data.forEach(function (element) {
                        // normalBalances.push(element)
                        let option = `<option value="${element.id}" ${element.id == subCategoryId ? 'selected' : ''}>${element.label}</option>`;

                        $('#normal_balance_edit').append(option);
                    });
                }
            });
            $('#amount_edit').val(amount)
        })
    });
</script>
@endpush
