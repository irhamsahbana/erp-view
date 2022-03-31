<?php

namespace App\Http\Controllers;

use App\Models\BudgetItem;
use Illuminate\Http\Request;
use App\Models\SubBudgetItem;
use App\Models\BudgetItemGroup;
use App\Models\Category;

class AjaxController extends Controller
{
    public function getBudgetItem(Request $request)
    {
        if($request->id == null)
            $dataBudgetItem = BudgetItem::all();
        else
            $dataBudgetItem = BudgetItem::where('budget_item_group_id', $request->id)->get();

        return $dataBudgetItem;
    }
    public function getSubBudgetItem(Request $request)
    {
        if($request->id == null)
            $dataSubBudgetItem = SubBudgetItem::all();
        else
            $dataSubBudgetItem = SubBudgetItem::where('budget_item_id', $request->id)->get();

        return $dataSubBudgetItem;
    }
    public function getBudgetItemGroup(Request $request)
    {
        $dataBudgetItemGroup = BudgetItemGroup::all();
        return $dataBudgetItemGroup;
    }
    public function getNormalBalance(Request $request)
    {
        $dataNormalBalances = Category::where('group_by', 'normal_balances')->get();
        return $dataNormalBalances;
    }
}
