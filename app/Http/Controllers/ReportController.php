<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\BudgetItem;
use App\Models\SubJournal;
use Illuminate\Http\Request;
use App\Models\SubBudgetItem;
use App\Models\BudgetItemGroup;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    public function balanceSheet(Request $request)
    {
        if(request('branch_id')||request('journal_category_id')||request('date_start')||request('date_finish')){

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

            if ($request->branch_id)
                $query->where('journals.branch_id', $request->branch_id);

            if ($request->project_id)
                $query->where('sub_journals.project_id', $request->project_id);

            if ($request->date_start)
                $query->whereDate('journals.created', '>=', new \DateTime($request->date_start));

            if ($request->date_finish)
                $query->whereDate('journals.created', '<=', new \DateTime($request->date_finish));

            $reportBalanceSheet = Category::where('slug', 'neraca')->first();
            $subBudgetItems = SubBudgetItem::where('report_category_id', $reportBalanceSheet->id)->get();
            $budgetItems = BudgetItem::where('report_category_id', $reportBalanceSheet->id)->get();
            $budgetItemGroups = BudgetItemGroup::where('report_category_id', $reportBalanceSheet->id)->get();
            $sbi = [];
            $bi = [];
            $big = [];

            $subJournals1 = $query->get();

            foreach ($subBudgetItems as $subBudgetItem) {
                $tmp = [];
                $total = 0.00;

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

                $tmp['total'] = $total;

                $sbi[] = $tmp;
            }

            $sbi = collect($sbi);

            foreach ($budgetItems as $budgetItem) {
                $tmp = [];
                $total = $sbi->where('budget_item_id', $budgetItem->id)->sum('total');

                $tmp['budget_item_group_id'] = $budgetItem->budget_item_group_id;
                $tmp['budget_item_id'] = $budgetItem->id;
                $tmp['sub_budget_item_id'] = $budgetItem->id;
                $tmp['name'] = $budgetItem->name;
                $tmp['total'] = $total;
                $bi[] = $tmp;
            }

            $bi = collect($bi);

            foreach ($budgetItemGroups as $budgetItemGroup) {
                $tmp = [];
                $total = $sbi->where('budget_item_group_id', $budgetItemGroup->id)->sum('total');

                $tmp['budget_item_group_id'] = $budgetItemGroup->id;
                $tmp['name'] = $budgetItemGroup->name;
                $tmp['total'] = $total;

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
            $balances = $report; 

        }else{
            $balances = [];
        }
        
        $options = self::staticOptions();

        return view('pages.BalanceSheetIndex', compact('balances', 'options'));
    }

    public function incomeStatement(Request $request)
    {
        if(request('branch_id')||request('journal_category_id')||request('date_start')||request('date_finish')){

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

            if ($request->branch_id)
                $query->where('journals.branch_id', $request->branch_id);

            if ($request->project_id)
                $query->where('sub_journals.project_id', $request->project_id);

            if ($request->date_start)
                $query->whereDate('journals.created', '>=', new \DateTime($request->date_start));

            if ($request->date_finish)
                $query->whereDate('journals.created', '<=', new \DateTime($request->date_finish));

            $reportIncomeStatementSheet = Category::where('slug', 'laba-rugi')->first();
            $subBudgetItems = SubBudgetItem::where('report_category_id', $reportIncomeStatementSheet->id)->get();
            $budgetItems = BudgetItem::where('report_category_id', $reportIncomeStatementSheet->id)->get();
            $budgetItemGroups = BudgetItemGroup::where('report_category_id', $reportIncomeStatementSheet->id)->get();

            $sbi = [];
            $bi = [];
            $big = [];

            $subJournals1 = $query->get();

            foreach ($subBudgetItems as $subBudgetItem) {
                $tmp = [];
                $total = 0.00;

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

                $tmp['total'] = $total;

                $sbi[] = $tmp;
            }

            $sbi = collect($sbi);

            foreach ($budgetItems as $budgetItem) {
                $tmp = [];
                $total = $sbi->where('budget_item_id', $budgetItem->id)->sum('total');

                $tmp['budget_item_group_id'] = $budgetItem->budget_item_group_id;
                $tmp['budget_item_id'] = $budgetItem->id;
                $tmp['sub_budget_item_id'] = $budgetItem->id;
                $tmp['name'] = $budgetItem->name;
                $tmp['total'] = $total;
                $bi[] = $tmp;
            }

            $bi = collect($bi);

            foreach ($budgetItemGroups as $budgetItemGroup) {
                $tmp = [];
                $total = $sbi->where('budget_item_group_id', $budgetItemGroup->id)->sum('total');

                $tmp['budget_item_group_id'] = $budgetItemGroup->id;
                $tmp['name'] = $budgetItemGroup->name;
                $tmp['total'] = $total;

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

        }else{
            $incomes = [];
        }

        $options = self::staticOptions();

        return view('pages.IncomeStatementIndex', compact('incomes', 'options'));
    }
    
    public static function staticOptions()
    {
        $branches = Branch::all();
        $category = Category::where('group_by', 'journal_categories')->get();

        if (!in_array(Auth::user()->role, self::$fullAccess))
            $branches = $branches->where('id', Auth::user()->branch_id);

        if ($branches->isNotEmpty()) {
            $branches = $branches->map(function ($branch) {
                return [
                    'text' => $branch->name,
                    'value' => $branch->id,
                ];
            });
        }

        if ($category->isNotEmpty()) {
            $category = $category->map(function ($category) {
                return [
                    'text' => $category->label,
                    'value' => $category->id,
                ];
            });
        }
        $options = [
            'branches' => $branches,
            'categories' => $category,
        ];

        return $options;
    }
}
