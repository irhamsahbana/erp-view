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

@section('content-header', 'Laba Rugi')

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
                        <a href="{{ route('income.statement.index') }}" type="reset" class="btn btn-default">reset</a>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </x-col>

                </x-row>
            </form>
        </x-card-collapsible>

        <x-card-collapsible>
            @if(request('branch_id')||request('journal_category_id')||request('year'))
            <x-table :thead="['Data Laba/Rugi', 'Anggaran',  'Realisasi '. request('year') ?? '', 'Realisasi '. request('year') - 1 ?? 'Tahun Sebelumnya', 'Selisih']">
                @foreach ($incomes as $income)
                    @if ($income['name'] == 'Pendapatan')
                    <?php
                        $anggaranPendapatanTotal = $income['total_budget'];
                        $PendapatanTotal = $income['total'];
                        $PendapatanTotalBefore = $income['total_before'];
                    ?>
                    @endif
                    @if ($income['name'] == 'HPP')
                    <?php
                        $anggaranHPPTotal = $income['total_budget'];
                        $HPPTotal = $income['total'];
                        $HPPTotalBefore = $income['total_before']
                    ?>
                    @endif
                    @if ($income['name'] == 'Biaya')
                    <?php
                        $anggaranBiayaTotal = $income['total_budget'];
                        $biayaTotal = $income['total'];
                        $biayaTotalBefore = $income['total_before'];
                    ?>
                    @endif
                    <tr>
                        <td></td>
                        <td class="pl-1"><h6>{{ $income['name'] }}</h6></td>
                        <td>Rp. {{ number_format($income['total_budget'], 2) }}</td>
                        <td><h6>Rp. {{ number_format($income['total'], 2) }}</h6></td>
                        <td><h6>Rp. {{ number_format($income['total_before'], 2) }}</h6></td>
                        <?php $selisihIncome = $income['total_budget'] - $income['total'] ?>
                        <td>Rp. {{ number_format((float)$selisihIncome, 2) }}</td>
                    </tr>
                    @foreach ($income['budget_items'] as $budgetItem)
                        <tr>
                            <td></td>
                            <td class="pl-3"><h6>{{ $budgetItem['name'] }}</h6></td>
                            <td>Rp. {{ number_format($budgetItem['total_budget'], 2) }}</td>
                            <td><h6>Rp. {{ number_format($budgetItem['total'], 2) }}</h6></td>
                            <td><h6>Rp. {{ number_format($budgetItem['total_before'], 2) }}</h6></td>
                            <?php $selisihBudgetItem = $budgetItem['total_budget'] - $budgetItem['total'] ?>
                            <td>Rp. {{ number_format((float)$selisihBudgetItem, 2) }}</td>
                        </tr>
                        @foreach ($budgetItem['sub_budget_items'] as $subBudgetItem)
                            <tr>
                                <td></td>
                                <td class="pl-5"><h6>{{ $subBudgetItem['name'] }}</h6></td>
                                <td>Rp. {{ number_format($subBudgetItem['total_budget'], 2) }}</td>
                                <td><h6>Rp. {{ number_format($subBudgetItem['total'], 2) }}</h6></td>
                                <td><h6>Rp. {{ number_format($subBudgetItem['total_before'], 2) }}</h6></td>
                                <?php $selisihSubBudgetItem = $subBudgetItem['total_budget'] - $subBudgetItem['total'] ?>
                                <td>Rp. {{ number_format((float)$selisihSubBudgetItem, 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    @if ($income['name'] == 'HPP')
                        <tr>
                            <td></td>
                            <td><h4 class="text-primary">Laba Kotor</h4></td>
                            <?php 
                                $labaKotor = $PendapatanTotal - $HPPTotal;
                                $labaKotorBefore = $PendapatanTotalBefore - $HPPTotalBefore;
                                $anggaranLabaKotor= $anggaranPendapatanTotal - $anggaranHPPTotal;
                                $selisihLabaKotor = $anggaranLabaKotor - $labaKotor;
                            ?>
                            <td><h5 class="text-primary">Rp. {{ number_format((float)$anggaranLabaKotor, 2) }}</h5></td>
                            <td><h5 class="text-primary">Rp. {{ number_format((float)$labaKotor, 2) }}</h5></td>
                            <td><h5 class="text-primary">Rp. {{ number_format((float)$labaKotorBefore,2) }}</h5></td>
                            <td><h5 class="text-primary">Rp. {{ number_format((float)$selisihLabaKotor, 2) }}</h5></td>
                        </tr>
                    @endif
                    @if ($income['name'] == 'Biaya')
                        <tr>
                            <td></td>
                            <td><h4 class="text-primary">Laba Bersih</h4></td>
                            <?php 
                                $labaBersih = $PendapatanTotal - $HPPTotal - $biayaTotal;
                                $labaBersihBefore = $PendapatanTotalBefore - $HPPTotalBefore - $biayaTotalBefore;
                                $anggaranLabaBersih = $anggaranPendapatanTotal - $anggaranHPPTotal - $anggaranBiayaTotal;
                                $selisihLabaBersih = $anggaranLabaBersih - $labaBersih;
                            ?>
                            <td><h5 class="text-primary">Rp. {{ number_format((float)$anggaranLabaBersih, 2) }}</h5></td>
                            <td><h5 class="text-primary">Rp. {{ number_format((float)$labaBersih, 2) }}</h5></td>
                            <td><h5 class="text-primary">Rp. {{ number_format((float)$labaBersihBefore, 2) }}</h5></td>
                            <td><h5 class="text-primary">Rp. {{ number_format((float)$selisihLabaBersih, 2) }}</h5></td>
                        </tr>
                    @endif
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
<meta name="search-date-finish" content="{{ app('request')->input('date_finish') ?? null }}">>

<meta name="url-branch" content="{{ route('branch.index') }}">
<meta name="url-project" content="{{ route('project.index') }}">

{{-- Searching --}}
<script>
    $(function () {
            let selectBranch = $('#branch_id');
            let selectProject = $('#project_id');
            // let selectVendor = $('#vendor_id');

            selectBranch.on('change', function () {
                let branchId = $(this).val();
                let searchProject = $('meta[name="search-project"]').attr('content');
                // let searchVendor = $('meta[name="search-vendor"]').attr('content');

                if (branchId == '') {
                    selectProject.empty();
                    selectProject.append('<option value="">Pilih Proyek</option>');

                    selectVendor.empty();
                    selectVendor.append('<option value="">Pilih Vendor</option>');

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