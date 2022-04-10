@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Budget'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Detail Budget')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('budget.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <x-row>
                            <x-in-select
                                :label="'Cabang'"
                                :placeholder="'Pilih Cabang'"
                                :col="6"
                                :id="'in_branch_id'"
                                :name="'branch_id'"
                                :options="$options['branches']"
                                :value="$data->branch_id"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Proyek'"
                                :placeholder="'Pilih Proyek'"
                                :col="6"
                                :id="'in_project_id'"
                                :name="'project_id'"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Kelompok Mata Anggaran'"
                                :placeholder="'Pilih Kelompok Mata Anggaran'"
                                :col="6"
                                :id="'budget_item_group_id_in'"
                                :name="'budget_item_group_id'"
                                :options="$options['groups']"
                                :value="$data->budget_item_group_id"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Mata Anggaran'"
                                :placeholder="'Pilih Mata Anggaran'"
                                :col="6"
                                :id="'budget_item_id_in'"
                                :name="'budget_item_id'"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Sub Mata Anggaran'"
                                :placeholder="'Pilih Sub Mata Anggaran'"
                                :col="4"
                                :id="'sub_budget_item_id_in'"
                                :name="'sub_budget_item_id'"
                                :required="true"></x-in-select>
                            <x-in-text
                                :type="'number'"
                                :label="'Total Harga'"
                                :col="4"
                                :id="'in_amount'"
                                :name="'amount'"
                                :value="$data->amount"
                                :required="true"></x-in-text>
                            <x-in-select
                                :type="'number'"
                                :label="'Tahun'"
                                :col="4"
                                :id="'in_created'"
                                :name="'created'"
                                :options="$options['years']"
                                :value="$data->created"
                                :required="true"></x-in-select>
                            <x-col class="text-right">
                                <a href="{{ route('budget.index') }}" type="button" class="btn btn-default">Tutup</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </x-col>
                        </x-row>
                    </form>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection

@push('js')
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-branch" content="{{ Auth::user()->branch_id ?? null }}">

    <meta name="search-project" content="{{ app('request')->input('project_id') ?? null }}">

    <meta name="old-project" content="{{ old('project_id') ?? null }}">

    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-big" content="{{ route('big.index') }}">
    <meta name="url-bi" content="{{ route('bi.index') }}">
    <meta name="url-sbi" content="{{ route('sbi.index') }}">

    <meta name="data-project" content={{ $data->project_id ?? null }}>
    <meta name="data-bi" content={{ $data->budget_item_id ?? null }}>
    <meta name="data-sbi" content={{ $data->sub_budget_item_id ?? null }}>

    <script>
       $(function () {
            let selectBranchIn = $('#in_branch_id');
            let selectProjectIn = $('#in_project_id');
            let selectBudgetItemGroup = $('#budget_item_group_id_in');
            let selectBudgetItem = $('#budget_item_id_in');
            let selectSubBudgetItem = $('#sub_budget_item_id_in');

            selectBranchIn.on('change', function () {
                let branchId = $(this).val();
                let dataProject = $('meta[name="data-project"]').attr('content');

                if (branchId == '') {
                    selectProjectIn.empty();
                    selectProjectIn.append('<option value="">Pilih Proyek</option>');

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

                        if (dataProject != '') {
                            selectProjectIn.val(dataProject).trigger('change');
                        }
                    }
                });

            });

            if (selectBranchIn.val() != '')
                selectBranchIn.trigger('change');


            selectBudgetItemGroup.on('change', function () {
                let groupId = $(this).val();
                let dataBudget = $('meta[name=data-bi]').attr('content');

                selectSubBudgetItem.empty();
                selectSubBudgetItem.append('<option value="">Pilih Sub Mata Anggaran</option>');

                if (groupId == '') {
                    selectBudgetItem.empty();
                    selectBudgetItem.append('<option value="">Pilih Mata Anggaran</option>');

                    return;
                }

                // Get budget item
                $.ajax({
                    url: $('meta[name="url-bi"]').attr('content'),
                    type: 'GET',
                    data: {
                        budget_item_group_id: groupId,
                    },
                    success: function (data) {
                        selectBudgetItem.empty();
                        selectBudgetItem.append(`<option value="">Pilih Mata Anggaran</option>`);

                        data.datas.forEach(function(item) {
                            selectBudgetItem.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectBudgetItem.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Mata Anggaran',
                            allowClear: true,
                        });
                        if (dataBudget != '') {
                            selectBudgetItem.val(dataBudget).trigger('change');
                        }
                    }
                });

            });

            selectBudgetItem.on('change', function(){
                let itemId = $(this).val();
                let dataSubBudget = $('meta[name=data-sbi]').attr('content');

                if(itemId == ''){
                    selectSubBudgetItem.empty();
                    selectSubBudgetItem.append('<option value="">Pilih Sub Mata Anggaran</option>');

                    return;
                }

                 // Get sub budget item
                $.ajax({
                    url: $('meta[name="url-sbi"]').attr('content'),
                    type: 'GET',
                    data: {
                        budget_item_id: itemId,
                    },
                    success: function (data) {
                        selectSubBudgetItem.empty();
                        selectSubBudgetItem.append(`<option value="">Pilih Sub Mata Anggaran</option>`);

                        data.datas.forEach(function(item) {
                            selectSubBudgetItem.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectSubBudgetItem.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Sub Mata Anggaran',
                            allowClear: true,
                        });
                    }
                });

                if (dataSubBudget != '') {
                    selectSubBudgetItem.val(dataSubBudget).trigger('change');
                }
            });

            if (selectBranchIn.val() != '')
                selectBranchIn.trigger('change');

            if (selectBudgetItemGroup.val() != '')
                selectBudgetItemGroup.trigger('change');

            if (selectBudgetItem.val() != '')
                selectBudgetItem.trigger('change');
        });
    </script>
@endpush