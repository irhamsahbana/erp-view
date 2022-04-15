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
    for($i = $dateNow + 5; $i>= 1980; $i--){
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
                            :required="true">
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
                            :name="'year'"
                            :options="$year"
                            :value="app('request')->input('year') ?? null"
                            :required="true">
                        </x-in-select>
                        <x-col class="text-right">
                            <a href="{{ route('balance.index') }}" type="reset" class="btn btn-default">reset</a>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </x-col>
                    </x-row>
                </form>
            </x-card-collapsible>

            <x-card-collapsible>
                @if(request('branch_id')||request('journal_category_id')||request('year'))


                <x-table :thead="['Data Neraca', 'Anggaran',  'Realisasi '.request('year') ?? 'Tahun', 'Realisasi '.request('year') - 1 ?? 'Tahun Sebelumnya', 'Selisih']">
                    @foreach ($balances as $balance)
                        <tr>
                            <td></td>
                            <td class="pl-1"><h6>{{ $balance['name'] }}</h6></td>
                            <td>Rp. {{ number_format($balance['total_budget'], 2) }}</td>
                            <td><h6>Rp. {{ number_format($balance['total'], 2) }}</h6></td>
                            <td><h6>Rp. {{ number_format($balance['total_before'], 2) }}</h6></td>
                            <?php $selisihBalance = $balance['total_budget'] - $balance['total']; ?>
                            <td>Rp. {{ number_format((float)$selisihBalance, 2) }}</td>
                        </tr>
                        @foreach ($balance['budget_items'] as $budgetItem)
                            <tr>
                                <td></td>
                                <td class="pl-3"><h6>{{ $budgetItem['name'] }}</h6></td>
                                <td>Rp. {{ number_format($budgetItem['total_budget'], 2) }}</td>
                                <td><h6>Rp. {{ number_format($budgetItem['total'], 2) }}</h6></td>
                                <td><h6>Rp. {{ number_format($budgetItem['total_before'], 2) }}</h6></td>
                                <?php $selisihBudgetItem = $budgetItem['total_budget'] - $budgetItem['total']; ?>
                                <td>Rp. {{ number_format((float)$selisihBudgetItem, 2) }}</td>
                            </tr>
                            @foreach ($budgetItem['sub_budget_items'] as $subBudgetItem)
                                <tr>
                                    <td></td>
                                    <td class="pl-5"><h6>{{ $subBudgetItem['name'] }}</h6></td>
                                    <td>Rp. {{ number_format($subBudgetItem['total_budget'], 2) }}</td>
                                    <td><h6>Rp. {{ number_format($subBudgetItem['total'], 2) }}</h6></td>
                                    <td><h6>Rp. {{ number_format($subBudgetItem['total_before'], 2) }}</h6></td>
                                    <?php $selisihSubBudgetItem = $subBudgetItem['total_budget'] - $subBudgetItem['total']; ?>
                                    <td>Rp. {{ number_format((float)$selisihSubBudgetItem, 2) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </x-table>
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


