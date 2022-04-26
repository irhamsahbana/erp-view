<x-table :thead="['Kelompok Mata Anggaran', 'Mata Anggaran', 'Sub Mata Anggaran', 'Anggaran',  'Realisasi '.(int)app('request')->input('year'), 'Realisasi '.((int)app('request')->input('year') - 1), 'Selisih']">
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
            <td></td>
            <td></td>
            <td>{{ $income['total_budget'] }}</td>
            <td><h6>{{ $income['total'] }}</h6></td>
            <td><h6>{{ $income['total_before'] }}</h6></td>
            <?php $selisihIncome = $income['total_budget'] - $income['total'] ?>
            <td>{{ $selisihIncome }}</td>
        </tr>
        @foreach ($income['budget_items'] as $budgetItem)
            <tr>
                <td></td>
                <td></td>
                <td class="pl-3"><h6>{{ $budgetItem['name'] }}</h6></td>
                <td></td>
                <td>{{ $budgetItem['total_budget'] }}</td>
                <td><h6>{{ $budgetItem['total'] }}</h6></td>
                <td><h6>{{ $budgetItem['total_before'] }}</h6></td>
                <?php $selisihBudgetItem = $budgetItem['total_budget'] - $budgetItem['total'] ?>
                <td>{{ $selisihBudgetItem }}</td>
            </tr>
            @foreach ($budgetItem['sub_budget_items'] as $subBudgetItem)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="pl-5"><h6>{{ $subBudgetItem['name'] }}</h6></td>
                    <td>{{ $subBudgetItem['total_budget'] }}</td>
                    <td><h6>{{ $subBudgetItem['total'] }}</h6></td>
                    <td><h6>{{ $subBudgetItem['total_before'] }}</h6></td>
                    <?php $selisihSubBudgetItem = $subBudgetItem['total_budget'] - $subBudgetItem['total'] ?>
                    <td>{{ $selisihSubBudgetItem }}</td>
                </tr>
            @endforeach
        @endforeach
        @if ($income['name'] == 'HPP')
            <tr>
                <td></td>
                <td><h4 class="text-primary">Laba Kotor</h4></td>
                <td></td>
                <td></td>
                <?php 
                    $labaKotor = $PendapatanTotal - $HPPTotal;
                    $labaKotorBefore = $PendapatanTotalBefore - $HPPTotalBefore;
                    $anggaranLabaKotor= $anggaranPendapatanTotal - $anggaranHPPTotal;
                    $selisihLabaKotor = $anggaranLabaKotor - $labaKotor;
                ?>
                <td><h5 class="text-primary">{{ $anggaranLabaKotor }}</h5></td>
                <td><h5 class="text-primary">{{ $labaKotor }}</h5></td>
                <td><h5 class="text-primary">{{ $labaKotorBefore }}</h5></td>
                <td><h5 class="text-primary">{{ $selisihLabaKotor }}</h5></td>
            </tr>
        @endif
        @if ($income['name'] == 'Biaya')
            <tr>
                <td></td>
                <td><h4 class="text-primary">Laba Bersih</h4></td>
                <td></td>
                <td></td>
                <?php 
                    $labaBersih = $PendapatanTotal - $HPPTotal - $biayaTotal;
                    $labaBersihBefore = $PendapatanTotalBefore - $HPPTotalBefore - $biayaTotalBefore;
                    $anggaranLabaBersih = $anggaranPendapatanTotal - $anggaranHPPTotal - $anggaranBiayaTotal;
                    $selisihLabaBersih = $anggaranLabaBersih - $labaBersih;
                ?>
                <td><h5 class="text-primary">{{ $anggaranLabaBersih }}</h5></td>
                <td><h5 class="text-primary">{{ $labaBersih }}</h5></td>
                <td><h5 class="text-primary">{{ $labaBersihBefore }}</h5></td>
                <td><h5 class="text-primary">{{ $selisihLabaBersih }}</h5></td>
            </tr>
        @endif
    @endforeach
</x-table>