<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use App\Models\Receivable;
use App\Models\Voucher;
use App\Models\Bill;
use App\Models\SubJournal;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Http\Request;


class DashboardController extends Controller
{

    public function dashboard(Request $request) {
        // dd("haloo");


        $date = Carbon::now()->format("Y-m-d");
        $voucer = Voucher::select('*');

        if(Auth::user()->role !== "owner") {
            $voucer->where("branch_id", Auth::user()->branch_id);
        }
        if($request->branch_id) $voucer->where('branch_id', $request->branch_id);
        $voucer = $voucer->get();
        $old_cash_out = $voucer->where('created',"<", $date)->where('type', 2)->sum('amount');
        $old_cash_in = $voucer->where('created',"<", $date)->where('type', 1)->sum('amount');
        $set_balance = $old_cash_in - $old_cash_out;
        // dd($old_cash_in);
        $cash_in = $voucer->where('created', $date)->where('type', 1)->sum('amount');
        $cash_out = $voucer->where('created', $date)->where('type', 2)->sum('amount');
        $total_cash = $set_balance + $cash_in - $cash_out;


        // Piutang
        $receivable = Receivable::select('*')->get();
        if (!in_array(Auth::user()->role, self::$fullAccess))
        $receivable->where('branch_id', Auth::user()->branch_id);
        if($request->branch_id) $receivable->where('branch_id', $request->branch_id);

        $receivable_total = $receivable->where('is_paid', 0)->sum('amount');
        $receivable_duedate = $receivable->where('is_paid', 0)
        ->where('due_date', '<=', $date)
        ->sum('amount');


        // Tagihan
        $bill = Bill::select("*");
        if (!in_array(Auth::user()->role, self::$fullAccess))
        $bill->where('branch_id', Auth::user()->branch_id);
        if($request->branch_id) $bill->where('branch_id', $request->branch_id);

        $bill = $bill->get();
        $bill_total = $bill->where('is_paid', false)->sum('amount');
        $bill_due_date = $bill->where('is_paid', false)->where('due_date', '<=', $date)->sum('amount');

        // Rugi Laba
        $subJournal = SubJournal::select("*");


        $subJournal = $subJournal->get();
        $income = $subJournal->where('budget_item_group_id', 1)->where('normal_balance_id', 12)->sum('amount') - $subJournal->where('budget_item_group_id', 1)->where('normal_balance_id', 11)->sum('amount');
        $cost = $subJournal->where('budget_item_group_id', 3)->where('normal_balance_id', 11)->sum('amount') + $subJournal->where('budget_item_group_id', 2)->where('normal_balance_id', 11)->sum('amount') - $subJournal->where('budget_item_group_id', 3)->where('normal_balance_id', 12)->sum('amount') - $subJournal->where('budget_item_group_id', 2)->where('normal_balance_id', 12)->sum('amount');

        $profit = $income - $cost;
        // dd($income);
        // options
        $options = self::staticOptions();

        return view('pages.Dashboard', compact( 'income', 'cost', 'profit', 'options', 'receivable_total', 'receivable_duedate', 'set_balance', 'cash_out', 'cash_in', 'total_cash', 'bill_total', 'bill_due_date'));
    }

    public function staticOptions() {
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
}
