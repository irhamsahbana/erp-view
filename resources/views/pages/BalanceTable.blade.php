<x-table :thead="['Kelompok Mata Anggaran', 'Mata Anggaran', 'Sub Mata Anggaran', 'Anggaran',  'Realisasi '.request('year') ?? 'Tahun', 'Realisasi '.request('year') - 1 ?? 'Tahun Sebelumnya', 'Selisih']">
    @foreach ($balances as $balance)
        <tr>
            <td></td>
            <td class="pl-1"><h6>{{ $balance['name'] }}</h6></td>
            <td></td>
            <td></td>
            <td>{{ $balance['total_budget'] }}</td>
            <td><h6>{{ $balance['total'] }}</h6></td>
            <td><h6>{{ $balance['total_before'] }}</h6></td>
            <?php $selisihBalance = $balance['total_budget'] - $balance['total']; ?>
            <td>{{ $selisihBalance }}</td>
        </tr>
        @foreach ($balance['budget_items'] as $budgetItem)
            <tr>
                <td></td>
                <td></td>
                <td class="pl-3"><h6>{{ $budgetItem['name'] }}</h6></td>
                <td></td>
                <td>{{ $budgetItem['total_budget'] }}</td>
                <td><h6>{{ $budgetItem['total'] }}</h6></td>
                <td><h6>{{ $budgetItem['total_before'] }}</h6></td>
                <?php $selisihBudgetItem = $budgetItem['total_budget'] - $budgetItem['total']; ?>
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
                    <?php $selisihSubBudgetItem = $subBudgetItem['total_budget'] - $subBudgetItem['total']; ?>
                    <td>{{ $selisihSubBudgetItem }}</td>
                </tr>
            @endforeach
        @endforeach
    @endforeach
</x-table>