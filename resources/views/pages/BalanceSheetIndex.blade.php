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

$dateNow = date("Y");
for($i = $dateNow; $i>= 1980; $i--){
    $year[] = [
        'text' => $i,
        'value' => $i,
    ];
}
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
                    <x-in-select 
                        :label="'Cabang'" 
                        :placeholder="'Pilih Cabang'" 
                        :col="4" 
                        :name="'branch_id'"
                        :options="$options['branches']" 
                        :value="app('request')->input('branch_id') ?? null"
                        :required="false">
                    </x-in-select>
                    <x-in-select 
                        :label="'Proyek'" 
                        :placeholder="'Pilih Proyek'" 
                        :col="4" 
                        :name="'project_id'"
                        :required="false">
                    </x-in-select>
                    <x-in-select 
                        :label="'Tahun'" 
                        :placeholder="'Pilih Tahun'" 
                        :col="4" 
                        :name="'date'"
                        :options="$year"
                        :value="app('request')->input('date') ?? null"
                        :required="false">
                    </x-in-select>
                    <x-col class="text-right">
                        <a href="{{ route('balance.index') }}" type="reset" class="btn btn-default">reset</a>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </x-col>
                </x-row>
            </form>
        </x-card-collapsible>

        <x-card-collapsible>
            @if(request('branch_id')||request('journal_category_id')||request('date'))

            @foreach ($balances as $balance)
            <x-row>
                <x-col :col='10'>
                    <h3>{{ $balance['name'] }}</h3>
                </x-col>
                <x-col :col='2'>
                    <span class="ml-1 text-right">
                        <h4>Rp. {{ number_format($balance['total'], 2) }}</h4>
                    </span>
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
                        <span class="ml-1 text-right">
                            <h4>Rp. {{ number_format($budgetItem['total'], 2) }}</h4>
                        </span>
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
                        <span class="ml-1 text-right">
                            <h4>Rp. {{ number_format($subBudgetItem['total'], 2) }}</h4>
                        </span>
                    </x-col>
                </x-row>
            </div>
            @endforeach
            @endforeach
            @endforeach
            @endif
        </x-card-collapsible>
    </x-row>
</x-content>
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