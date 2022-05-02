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

            <form style="width: 100%">
                <x-row>
                    <x-in-select
                        :label="'Cabang'"
                        :placeholder="'Pilih Cabang'"
                        :id="'select_branch'"
                        :name="'branch_id'"
                        :options="$options['branches']"
                        :value="app('request')->input('branch_id') ?? null"
                        :required="true"></x-in-select>
                </x-row>
                <x-row>
                    <x-in-select
                        :label="'Proyek'"
                        :placeholder="'Pilih Proyek'"
                        :col="3"
                        :id="'select_project'"
                        :name="'project_id'"
                        :required="false"
                        :value="app('request')->input('project_id') ?? null">
                    </x-in-select>

                    <x-in-select
                        :label="'Kelompok Mata Angggaran'"
                        :placeholder="'Pilih Kelompok Mata Anggaran'"
                        :col="3" :id="'select_budget_item_group'"
                        :name="'budget_item_group_id'"
                        :options="$options['budgetItemGroups']"
                        :required="true"
                        :value="app('request')->input('budget_item_group_id') ?? null">
                    </x-in-select>

                    <x-in-select
                        :label="'Mata Anggaran'"
                        :placeholder="'Pilih Mata Anggaran'"
                        :col="3"
                        :id="'select_budget_item'"
                        :name="'budget_item_id'"
                        :value="app('request')->input('budget_item_id') ?? null"
                        :required="true">
                    </x-in-select>

                    <x-in-select
                        :label="'Sub Mata Anggaran'"
                        :placeholder="'Pilih Sub Mata Anggaran'"
                        :col="3"
                        :id="'select_sub_budget_item'"
                        :name="'sub_budget_item_id'"
                        :required="true"
                        :value="app('request')->input('sub_budget_item_id') ?? null">
                    </x-in-select>
                </x-row>

                <x-row>

                    <x-in-text
                        :type="'date'"
                        :label="'Tanggal Mulai'"
                        :col="6"
                        :name="'date_start'"
                        :required="true"
                        :value="app('request')->input('date_start') ?? null">
                    </x-in-text>

                    <x-in-text
                        :type="'date'"
                        :label="'Tanggal Akhir'"
                        :col="6"
                        :name="'date_finish'"
                        :required="true"
                        :value="app('request')->input('date_finish') ?? null">
                    </x-in-text>
                </x-row>

                <x-col class="text-right">
                    <a
                        type="button"
                        class="btn btn-default"
                        href="{{ route('general.ledger.index') }}">reset
                    </a>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </x-col>
            </form>

        </x-card-collapsible>
        <x-card-collapsible
            :title="'Sub Jurnal'"
            :collapse="false">
            <x-row>

                <x-col>
                    @if (session('success'))
                    <div
                        class="alert alert-primary alert-dismissible fade show"
                        role="alert">
                        {{session('success') }}
                        <button type="button" class="close" data-dismiss="alert"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                </x-col>

                <div class="table-responsive">
                    <x-table
                        :thead="['Tanggal', 'No, Jurnal', 'Proyek', 'Kelompok MA', 'MA', 'Sub MA', 'Catatan', 'Posisi', 'Jumlah']">
                        <tr>
                            <td colspan="9">
                                <h5>Saldo Awal</h5>
                            </td>
                            <td class="text-right">{{ number_format($firstSaldo) }}</td>
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
                            <td class="text-right">{{ number_format($sub->amount) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="9">
                                <h5>Saldo Akhir</h5>
                            </td>
                            <td class="text-right"> {{ number_format($lastSaldo) }}</td>
                        </tr>
                    </x-table>
                </div>

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
        let selectBudgetItemGroups = $('#select_budget_item_group');
        let selectBudgetItem = $('#select_budget_item');
        let selectSubBudgetItem = $('#select_sub_budget_item');

        selectProject.select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Proyek',
            allowClear: true,
        });
        selectBudgetItemGroups.select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kelompok Mata Anggaran',
            allowClear: true,
        });
        selectBudgetItem.select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Mata Anggaran',
            allowClear: true,
        });
        selectSubBudgetItem.select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Sub Mata Anggaran',
            allowClear: true,
        });
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

                    if (searchProject != '') {
                        selectProject.val(searchProject).trigger('change');
                    }
                }
            });
        });

        if (selectBranch.val() != '')
            selectBranch.trigger('change');

        selectBudgetItemGroups.on('change', function(){
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
                    selectBudgetItem.empty();
                    selectBudgetItem.append(`<option value="">Pilih Mata Anggaran</option>`);

                    selectSubBudgetItem.empty();
                    selectSubBudgetItem.append(`<option value="">Pilih Sub Mata Anggaran</option>`);

                    data.forEach(element => {
                        let option = `<option value="${element.id}">${element.name}</option>`;

                        selectBudgetItem.append(option);
                    });
                }
            });
        });

        selectBudgetItem.on('change', function() {
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

                    selectSubBudgetItem.empty();
                    selectSubBudgetItem.append(`<option value="">Pilih Sub Mata Anggaran</option>`);

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
