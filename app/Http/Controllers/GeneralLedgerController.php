<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Journals;
use App\Models\SubJournal;
use Illuminate\Http\Request;
use App\Models\BudgetItemGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GeneralLedgerController extends Controller
{
    public function index(Request $request)
    {

        $query = self::getAllSubJournal();
        self::filters($request, $query);
        $subJournal = $query->get();

        $allJournals = self::getAllSubJournal();
        $allJournals2 = self::getAllSubJournal();
        $firstSaldo = 0;
        $lastSaldo = 0;
        $firstDebitCount = 0;
        $firstCreditCount = 0;
        $lastDebitCount = 0;
        $lastCreditCount = 0;
        
        foreach ($allJournals2->get() as $all) {
            if($all->sub_journal_balance_id == $all->sub_budget_item_balance_id){
                $lastDebitCount += $all->amount;
            }else{
                $lastCreditCount += $all->amount;
            }
        }
        $lastSaldo = $lastDebitCount - $lastCreditCount;

        // Set data
        if(count($subJournal) > 0){
            foreach ($allJournals->get() as $all) {
                if($all->id == $subJournal[0]['id']){
                    break;
                }else {
                    if($all->sub_journal_balance_id == $all->sub_budget_item_balance_id){
                        $firstDebitCount += $all->amount;
                    }else{
                        $firstCreditCount += $all->amount;
                    }
                }
            }
            $firstSaldo = $firstDebitCount - $firstCreditCount;
        }else{
            $firstSaldo = $lastSaldo;
        }

        
        $data = [
            'subJournal' => $subJournal,
            'budgetItemGroup' => BudgetItemGroup::all(),
            'firstSaldo' => $firstSaldo,
            'lastSaldo' => $lastSaldo,
            'options' => self::staticOptions(),
        ];
        return view('pages.GeneralLedgerIndex', $data);
    }
    public static function staticOptions()
    {
        $branches = Branch::all();

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
        $options = [
            'branches' => $branches,
        ];

        return $options;
    }
    public static function getAllSubJournal()
    {
        $query = SubJournal::select(
            'sub_journals.id',
            'sub_journals.project_id',
            'sub_journals.sub_budget_item_id',
            'sub_journals.normal_balance_id as sub_journal_balance_id',
            'sub_journals.amount',
            'journals.branch_id',
            'journals.created',
            'journals.ref_no',
            'journals.notes',
            'branches.name as branch_name',
            'projects.name as project_name',
            'budget_item_groups.name as budget_item_group_name',
            'budget_item_groups.id',
            'budget_items.name as budget_item_name',
            'budget_items.id',
            'sub_budget_items.name as sub_budget_item_name',
            'sub_budget_items.id', 
            'sub_budget_items.normal_balance_id as sub_budget_item_balance_id',
            'categories.label as category_name',
            )
            ->leftJoin('journals', 'journals.id', 'sub_journals.journal_id')
            ->leftJoin('branches', 'branches.id', 'journals.branch_id')
            ->leftJoin('projects', 'projects.id', 'sub_journals.project_id')
            ->leftJoin('budget_item_groups', 'budget_item_groups.id', 'sub_journals.budget_item_group_id')
            ->leftJoin('budget_items', 'budget_items.id', 'sub_journals.budget_item_id')
            ->leftJoin('sub_budget_items', 'sub_budget_items.id', 'sub_journals.sub_budget_item_id')
            ->leftJoin('categories', 'categories.id', 'sub_journals.normal_balance_id')->orderBy('journals.created', 'asc');

        return $query;
    }
    public static function filters($request, $query)
    {
         if($request->branch_id)      
            $query->where('journals.branch_id', $request->branch_id);
            
        if($request->project_id)
            $query->where('projects.id', $request->project_id);

        if($request->budget_item_group_id)
            $query->where('budget_item_groups.id', $request->budget_item_group_id);
        
        if($request->budget_item_id)
            $query->where('budget_items.id', $request->budget_item_id);

        if($request->sub_budget_item_id)
            $query->where('sub_budget_items.id', $request->sub_budget_item_id);

        if($request->date_start)
            $query->whereDate('journals.created', '>=', new \DateTime($request->date_start));

        if($request->date_finish)
            $query->whereDate('journals.created', '<=', new \DateTime($request->date_finish));
    } 
}
