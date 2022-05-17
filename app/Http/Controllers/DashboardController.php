<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use App\Models\Receivable;
use Carbon\Carbon;
use Illuminate\Http\Request;


class DashboardController extends Controller
{

    public function testDashboard() {
// $totalReceivable = Receivable::

$date = Carbon::now();
$query = Receivable::select('*');
if (!in_array(Auth::user()->role, self::$fullAccess))
$query->where('branch_id', Auth::user()->branch_id);
$lewatTempo = 0;

$receivable = $query->where('is_paid', 0)
                    // ->where('due_date', '<=', $date)
                    ->sum('amount');
$receivable_duedate = $query->where('is_paid', 0)
                    ->where('due_date', '<=', $date)
                    ->sum('amount');


        return view('pages.Dashboard', compact( 'receivable', 'receivable_duedate'));
    }
}
