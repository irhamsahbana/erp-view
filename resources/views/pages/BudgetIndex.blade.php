@extends('App')

@php
    $breadcrumbList = [
        [
            'name' => 'Home',
            'href' => '/'
        ],
        [
            'name' => 'Anggaran'
        ],
    ];
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@section('content-header', 'Anggaran')

@section('breadcrumb')
    <x-breadcrumb :list="$breadcrumbList"/>
@endsection

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible :title="'Pencarian'" :collapse="false">
                <form style="width: 100%">
                    <x-row>
                        <x-in-select
                            :label="'Cabang'"
                            :placeholder="'Pilih Cabang'"
                            :col="6"
                            :name="'branch_id'"
                            :options="$options['branches']"
                            :value="app('request')->input('branch_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :label="'Project'"
                            :placeholder="'Pilih Project'"
                            :col="6"
                            :name="'project_id'"
                            :value="app('request')->input('project_id') ?? null"
                            :required="false"></x-in-select>
                        <x-in-select
                            :type="'number'"
                            :label="'Tahun Mulai'"
                            :col="6"
                            :name="'date_start'"
                            :placeholder="'Pilih Tahun Mulai'"
                            :options="$options['years']"
                            :value="app('request')->input('date_start') ?? null"></x-in-select>
                        <x-in-select
                            :type="'number'"
                            :label="'Tahun Selesai'"
                            :col="6"
                            :name="'date_finish'"
                            :placeholder="'Pilih Tahun Selesai'"
                            :options="$options['years']"
                            :value="app('request')->input('date_finish') ?? null"></x-in-select>
                        <x-col class="text-right">
                            <a type="button" class="btn btn-default" href="{{ route('budget.index') }}">reset</a>
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
                        <x-table :thead="['Tahun', 'Cabang', 'Proyek', 'Sub Mata Anggaran', 'Anggaran', 'Status Close', 'Aksi']">
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->created }}</td>
                                    <td>{{ $data->branch->name }}</td>
                                    <td>{{ $data->project->name }}</td>
                                    <td>{{ $data->subBudgetItem->name }}</td>
                                    <td class="text-right">{{  number_format($data->amount) }}</td>
                                    <td>
                                        @if($data->is_open)
                                            <span class="badge badge-success">Open</span>
                                        @else
                                            <span class="badge badge-danger">Close</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(Auth::user()->role == 'owner' || Auth::user()->role == 'admin' || Auth::user()->role == 'purchaser')
                                        <form action="{{ route('budget.change-status', $data->id) }}" style="display:inline!important;" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button
                                                type="submit"
                                                class="btn btn-secondary"
                                                onclick="return confirm('Apakah anda ingin mengubah status pembayaran ini?')"
                                                title="ubah status"><i class="fas fa-sync-alt"></i></button>
                                        </form>
                                        @endif
                                        @if ($data->is_open)
                                        <form
                                            style=" display:inline!important;"
                                            method="POST"
                                            action="{{ route('budget.destroy', $data->id)}}">
                                                @csrf
                                                @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                        {{-- <a
                                            type="button"
                                            class="btn btn-warning"
                                            title="Edit"
                                            href="{{ route('budget.show', $data->id) }}"><i class="fas fa-pencil-alt"></i></a> --}}
                                        @endif
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
        <form style="width: 100%" action="{{ route('budget.store') }}" method="POST" id="add-form">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                    :label="'Cabang'"
                    :placeholder="'Pilih Cabang'"
                    :col="6"
                    :id="'in_branch_id'"
                    :name="'branch_id'"
                    :options="$options['branches']"
                    :value="old('branch_id')"
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
                    :required="true"></x-in-text>
                <x-in-select
                    :type="'number'"
                    :label="'Tahun'"
                    :col="4"
                    :id="'in_created'"
                    :name="'created'"
                    :options="$options['years']"
                    :required="true"></x-in-select>
                <x-col class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </x-col>
            </x-row>
        </form>
    </x-modal>

@endsection

@push('js')
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-branch" content="{{ Auth::user()->branch_id ?? null }}">

    <!-- Searching -->
    <meta name="search-branch" content="{{ app('request')->input('branch_id') ?? null }}">
    <meta name="search-project" content="{{ app('request')->input('project_id') ?? null }}">
    <meta name="search-date-start" content="{{ app('request')->input('date_start') ?? null }}">
    <meta name="search-date-finish" content="{{ app('request')->input('date_finish') ?? null }}">


    <meta name="search-project" content="{{ app('request')->input('project_id') ?? null }}">

    <meta name="old-project" content="{{ old('project_id') ?? null }}">

    <meta name="url-project" content="{{ route('project.index') }}">
    <meta name="url-big" content="{{ route('big.index') }}">
    <meta name="url-bi" content="{{ route('bi.index') }}">
    <meta name="url-sbi" content="{{ route('sbi.index') }}">

    <script>
        //  searching
        $(function(){
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            let selectDateStart = $('#date_start');
            let selectDateFinish = $('#date_finish');

            selectBranch.on('change', function(){
                let branchId = $(this).val();

                let searchProject = $('meta[name="search-project"]').attr('content');

                if (branchId == '') {
                    selectProject.empty();
                    selectProject.append('<option value="">Pilih Project</option>');

                    return;
                }

                // get project
                $.ajax({
                    url: $('meta[name="url-project"]').attr('content'),
                    type: "GET",
                    data: {
                        branch_id : branchId
                    },
                    success: function(data){
                        selectProject.empty();
                        selectProject.append('<option value="">Pilih Project</option>');

                        data.datas.forEach(function(item){
                            selectProject.append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        selectProject.select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Project',
                            allowClear: true
                        });

                        selectProject.val(searchProject).trigger('change');
                    }
                });
            });

            if(selectBranch.val() != ''){
                selectBranch.trigger('change');
            }
        });


       $(function () {
            $('#add-modal').on('hidden.bs.modal', function() {
                $('#add-form').trigger('reset');
            });

            let selectBranchIn = $('#in_branch_id');
            let selectProjectIn = $('#in_project_id');

            selectBranchIn.on('change', function () {
                let branchId = $(this).val();
                let searchProject = $('meta[name="search-project"]').attr('content');

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
            });

            if (selectBranchIn.val() != '')
                selectBranchIn.trigger('change');
        });

        $(document).ready(function() {
            $('#budget_item_group_id_in').on('change', function() {
                let budgetItemGroupId = $(this).val();

                if (budgetItemGroupId == '') {
                    $('#budget_item_id_in').empty();
                    $('#budget_item_id_in').append('<option value="">Pilih Mata Anggaran</option>');
                    return;
                }

                let url = $('meta[name="url-bi"]').attr('content');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        budget_item_group_id: budgetItemGroupId
                    },
                    cache: false,
                    success: function(data) {
                        $('#budget_item_id_in').empty();
                        $('#budget_item_id_in').append('<option value="">Pilih Mata Anggaran</option>');

                        data.datas.forEach(function(item) {
                            $('#budget_item_id_in').append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        //make using select2

                        $('#budget_item_id_in').select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Mata Anggaran',
                            allowClear: true
                        });
                    },
                    error: function(data) {
                        alert(data);
                    }
                });
            });

            $('#budget_item_id_in').on('change', function(){
                let budgetItemId = $(this).val();

                if(budgetItemId == ''){
                    $('#sub_budget_item_id_in').empty();
                    $('#sub_budget_item_id_in').append('<option value="">Pilih Sub Mata Anggaran</option>');

                    return;
                }

                let url = $('meta[name="url-sbi"]').attr('content');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        budget_item_id: budgetItemId
                    },
                    cache: false,
                    success: function(data){
                        $('#sub_budget_item_id_in').empty();
                        $('#sub_budget_item_id_in').append('<option value="">Pilih Sub Mata Anggaran</option>');

                        data.datas.forEach(function(item){
                            $('#sub_budget_item_id_in').append(`<option value="${item.id}">${item.name}</option>`);
                        });

                        $('#sub_budget_item_id_in').select2({
                            theme: 'bootstrap4',
                            placeholder: 'Pilih Sub Mata Anggaran',
                            allowClear: true
                        });
                    },
                    error: function(data){
                        alert(data);
                    }
                });
            });
        });

    </script>
@endpush
