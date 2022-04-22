<?php

namespace App\Exports;

use App\Models\Budget;
use App\Models\Category;
use App\Models\BudgetItem;
use App\Models\SubJournal;
use App\Models\SubBudgetItem;
use App\Models\BudgetItemGroup;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IncomeStatementView implements FromView, WithStyles, ShouldAutoSize
{
    private $branchId;
    private $projectId;
    private $year;
    public function __construct($branchId, $projectId, $year)
    {
        $this->branchId = $branchId;
        $this->projectId = $projectId;
        $this->year = $year;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
    public function view(): View
    {
        $query = self::getSubJournals();

        $query2 = self::getSubJournals();

        $budget = self::getBudget();


        if ($this->branchId){
            $query->where('journals.branch_id', $this->branchId);
            $query2->where('journals.branch_id', $this->branchId);
            $budget->where('branch_id', $this->branchId);
        }

        if ($this->projectId){
            $query->where('sub_journals.project_id', $this->projectId);
            $query2->where('sub_journals.project_id', $this->projectId);
            $budget->where('project_id', $this->projectId);
        }

        if ($this->year){
            $query->whereYear('journals.created', '<=',$this->year);
            $query2->whereYear('journals.created', '<=', $this->year - 1);
            $budget->where('created', '<=', $this->year);
        }

        $reportIncomeStatementSheet = Category::where('slug', 'laba-rugi')->first();
        $subBudgetItems = SubBudgetItem::where('report_category_id', $reportIncomeStatementSheet->id)->get();
        $budgetItems = BudgetItem::where('report_category_id', $reportIncomeStatementSheet->id)->get();
        $budgetItemGroups = BudgetItemGroup::where('report_category_id', $reportIncomeStatementSheet->id)->get();

        $sbi = [];
        $bi = [];
        $big = [];

        $subJournals1 = $query->get();

        $subJournalBefore1 = $query2->get();

        $budget1 = $budget->get();

        foreach ($subBudgetItems as $subBudgetItem) {
            $tmp = [];
            $total = 0.00;
            $totalBefore = 0.00;
            $totalBudget = 0.00;

            $tmp['budget_item_group_id'] = $subBudgetItem->budget_item_group_id;
            $tmp['budget_item_id'] = $subBudgetItem->budget_item_id;
            $tmp['name'] = $subBudgetItem->name;

            $subJournals =  $subJournals1->where('sub_budget_item_id', $subBudgetItem->id);

            foreach ($subJournals as $subJournal) {
            if ($subJournal->normal_balance_id == $subBudgetItem->normal_balance_id) {
                $total += $subJournal->amount;
                } else {
                    $total -= $subJournal->amount;
                }
            }

            // Get total before this request year
            $subJournalBefore = $subJournalBefore1->where('sub_budget_item_id', $subBudgetItem->id);

            foreach ($subJournalBefore as $subJournalBefore) {
                if ($subJournalBefore->normal_balance_id == $subBudgetItem->normal_balance_id) {
                    $totalBefore += $subJournalBefore->amount;
                } else {
                    $totalBefore -= $subJournalBefore->amount;
                }
            }

            $budget2 = $budget1->where('sub_budget_item_id', $subBudgetItem->id);
            foreach ($budget2 as $bg) {
                $totalBudget += $bg->amount;
            }



            $tmp['total'] = $total;
            $tmp['total_before'] = $totalBefore;
            $tmp['total_budget'] = $totalBudget;

            $sbi[] = $tmp;
        }

        $sbi = collect($sbi);

        foreach ($budgetItems as $budgetItem) {
            $tmp = [];
            $total = $sbi->where('budget_item_id', $budgetItem->id)->sum('total');
            $totalBefore = $sbi->where('budget_item_id', $budgetItem->id)->sum('total_before');
            $totalBudget = $sbi->where('budget_item_id', $budgetItem->id)->sum('total_budget');

            $tmp['budget_item_group_id'] = $budgetItem->budget_item_group_id;
            $tmp['budget_item_id'] = $budgetItem->id;
            $tmp['sub_budget_item_id'] = $budgetItem->id;
            $tmp['name'] = $budgetItem->name;
            $tmp['total'] = $total;
            $tmp['total_before'] = $totalBefore;
            $tmp['total_budget'] = $totalBudget;
            $bi[] = $tmp;
        }

        $bi = collect($bi);

        foreach ($budgetItemGroups as $budgetItemGroup) {
            $tmp = [];
            $total = $sbi->where('budget_item_group_id', $budgetItemGroup->id)->sum('total');
            $totalBefore = $sbi->where('budget_item_group_id', $budgetItemGroup->id)->sum('total_before');
            $totalBudget = $sbi->where('budget_item_group_id', $budgetItemGroup->id)->sum('total_budget');

            $tmp['budget_item_group_id'] = $budgetItemGroup->id;
            $tmp['name'] = $budgetItemGroup->name;
            $tmp['total'] = $total;
            $tmp['total_before'] = $totalBefore;
            $tmp['total_budget'] = $totalBudget;

            $big[] = $tmp;
        }

        $big = collect($big);

        $report = [];
        foreach ($big as $data) {
            $tmpBis = $bi->where('budget_item_group_id', $data['budget_item_group_id']);

            foreach($tmpBis as $tmpBi) {
                $tmpSbis = $sbi->where('budget_item_id', $tmpBi['budget_item_id']);

                $tmpBi['sub_budget_items'] = $tmpSbis;

                $data['budget_items'][] = $tmpBi;
            }

            $report[] = $data;
        }
        $incomes = $report;
        return view('pages.IncomeStatementTable', compact('incomes'));
    }
    public static function getSubJournals()
    {
        $query = SubJournal::select(
                'sub_journals.id',
                'sub_journals.project_id',
                'sub_journals.sub_budget_item_id',
                'sub_journals.normal_balance_id',
                'sub_journals.amount',
                'journals.branch_id',
                'journals.created'
                )
            ->leftJoin('journals', 'journals.id', 'sub_journals.journal_id');

        return $query;
    }
    public static function getBudget()
    {
        $query = Budget::select('*');
        return $query;
    }
}
