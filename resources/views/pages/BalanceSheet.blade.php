@extends('App')

@php
$breadcrumbList = [
[
'name' => 'Home',
'href' => '/'
],
[
'name' => 'Neraca'
],
];

$option = [
'value' => "test",
]
@endphp

@section('content-header', 'Neraca')
@push('css')
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

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
                    <x-in-select :label="'Proyek'" :placeholder="'Pilih Proyek'" :col="6" :name="'project_id'"
                        :required="false"></x-in-select>
                    <x-in-text :type="'date'" :label="'Tanggal Mulai'" :col="6"
                        :value="app('request')->input('date_start') ?? null" :name="'date_start'"></x-in-text>
                    <x-in-text :type="'date'" :label="'Tanggal Selesai'" :col="6"
                        :value="app('request')->input('date_finish') ?? null" :name="'date_finish'"></x-in-text>
                    <x-col class="text-right">
                        <a href="{{ route('balance.index') }}" type="reset" class="btn btn-default">reset</a>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </x-col>
                </x-row>
            </form>
        </x-card-collapsible>

        <x-card-collapsible>
            <x-row>
                <x-col>
                    @if (request('branch_id')||request('journal_category_id')||request('date_start')||request('date_finish'))
                        @foreach ($balances as $balance)
                        <x-row>
                           <x-col :col='10'>
                            <h3>{{ $balance['name'] }}</h3>
                           </x-col>
                           <x-col :col='2'>
                            <span class="ml-1 text-right"><h4>Rp. {{ number_format($balance['total'], 2) }}</h4></span>
                           </x-col>
                        </x-row>
                        {{-- <p>{{ $balance['budget_items']['total'] }}</p> --}}
                            @foreach ($balance['budget_items'] as $budgetItem)
                            <div class="pl-3">
                                <x-row>
                                    <x-col :col='10'>
                                        <h4>{{ $budgetItem['name'] }}</h4>
                                    </x-col>
                                    <x-col :col='2'>
                                        <span class="ml-1 text-right"><h4>Rp. {{ number_format($budgetItem['total'], 2) }}</h4></span>
                                    </x-col>
                                </x-row>
                            </div>
                                @foreach ($budgetItem['sub_budget_items'] as $subBudgetItem)
                                <div class="pl-5">
                                    <x-row>
                                        <x-col :col='10'>
                                            <h5>{{ $subBudgetItem['name'] }}</h5>
                                        </x-col>
                                        <x-col :col='2'>
                                            <span class="ml-1 text-right"><h4>Rp. {{ number_format($subBudgetItem['total'], 2) }}</h4></span>
                                        </x-col>
                                    </x-row>
                                </div>
                                @endforeach
                            @endforeach
                        @endforeach
                    </x-col>
                </x-row>
                @endif
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

<meta name="search-branch" content="{{ app('request')->input('branch_id') ?? null }}">
<meta name="search-project" content="{{ app('request')->input('project_id') ?? null }}">
<meta name="search-date-start" content="{{ app('request')->input('date_start') ?? null }}">
<meta name="search-date-finish" content="{{ app('request')->input('date_finish') ?? null }}">

<meta name="url-branch" content="{{ route('branch.index') }}">
<meta name="url-project" content="{{ route('project.index') }}">

<meta name="">

{{-- Searching --}}
<script>
    $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');

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
        });
</script>
@endpush