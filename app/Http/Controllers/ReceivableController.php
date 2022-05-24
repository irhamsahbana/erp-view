<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;

use App\Models\ReceivableVendor;
use App\Models\ReceivableBalance;
use App\Models\Branch;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use App\Models\Receivable as Model;
use Illuminate\Validation\Rule;

use Error;

class ReceivableController extends Controller
{
    //

    public function index(Request $request) {
        $query = Model::select('*');
        $date = Carbon::now();
        if (!in_array(Auth::user()->role, self::$fullAccess))
        $query->where('branch_id', Auth::user()->branch_id);

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
            }

            if($request->project_id) {
                $query->where('project_id', $request->project_id);
            }
            if($request->receivable_vendor_id) {
                $query->where('receivable_vendor_id', $request->receivable_vendor_id);
            }
            // send date
            if ($request->send_date_start)
            $query->whereDate('send_date', '>=', new \DateTime($request->send_date_start));

            if ($request->send_date_finish)
                $query->whereDate('send_date', '<=', new \DateTime($request->send_date_finish));

            if ($request->pay_date_start)
            $query->where('is_paid', true)->whereDate('pay_date', '>=', new \DateTime($request->pay_date_start));

            if ($request->pay_date_finish)
            // dd("Tes");
            // $query->where('is_paid', false);
                $query->whereDate('pay_date', '<=', new \DateTime($request->pay_date_finish));

            if ($request->due_date_start)
            $query->whereDate('due_date', '>=', new \DateTime($request->due_date_start));

            if ($request->due_date_finish)
                $query->whereDate('due_date', '<=', new \DateTime($request->due_date_finish));

            $total = $query->sum('amount');
            // echo($request->send_date_start);
            // dd($total);

            $datas = $query->paginate(40)->withQueryString();
            $query->orderBy('send_date', 'desc');
            $options = self::staticOptions();


            // $query = Model::select('*');


            $receivable = $query->where('is_paid', 0)
            // ->where('due_date', '<=', $date)
            ->sum('amount');
            $receivable_duedate = $query->where('is_paid', 0)
            ->where('due_date', '<=', $date)
            ->sum('amount');

        return view('pages.Receivable', compact('datas', 'options', 'total', 'receivable', 'receivable_duedate'));
    }

    public function store(Request $request) {

        // $request->validate([
        //     'id' => ['nullable'],
        //     'amount' => ['required', 'numeric'],
        //     'notes' => ['required', 'string', 'max:255'],
        //     'is_open' => ['nullable', 'boolean'],
        //     'send_date' => ['required', 'date'],
        //     'branch_id' => ['required', 'exists:branches,id'],
        //     'project_id' => ['required', 'exists:projects,id'],
        //     'receivable_vendor_id' => ['required', 'exists:receivable_vendor,id'],

        // ]);
        dd($request);
        $row=Model::findOrNew($request->id);
        $row->branch_id = $request->modal_branch_id;
        $row->amount = $request->amount;
        $row->notes = $request->notes;
        $row->save();
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
    public function addReceivable(Request $request) {

        // dd($request);
        $request->validate([
            'id' => ['nullable'],
            // 'new_amount' => ['required', 'numeric'],
            'new_notes' => ['required', 'string', 'max:255'],
            'new_send_date' => ['required', 'date'],
            'new_send_date' => ['required', 'date'],
            'new_branch_id' => ['required', 'exists:branches,id'],
            'new_project_id' => ['required', 'exists:projects,id'],
            'new_receivable_vendor_id' => ['required', 'exists:receivable_vendor,id'],

        ]);

        $balance = ReceivableBalance::firstOrNew([
                    'branch_id' => $request->new_branch_id,
                    'project_id'=> $request->new_project_id,
                    'receivable_vendor_id'=> $request->new_receivable_vendor_id
                ]);

        $balance->receivable_vendor_id = $request->new_receivable_vendor_id;

        $row = Model::findOrNew($request->id);
        $prefix = sprintf('%s/', $row->getTable());
        $postfix = sprintf('/%s.%s', date('m'), date('y'));
        $row->ref_no = $this->generateRefNo($row->getTable(), 4, $prefix, $postfix);
        $row->amount = $request->new_amount;
        $row->send_date = $request->new_send_date;
        $row->due_date = $request->new_due_date;
        $row->notes = $request->new_notes;
        $row->receivable_vendor_id = $request->new_receivable_vendor_id;
        $row->branch_id = $request->new_branch_id;
        $row->project_id = $request->new_project_id;

        $balance->amount += $request->new_amount;
        $balance->save();
        $row->save();
        return redirect()->back()->with('f-msg', 'Data berhasil disimpan.');

    }

    public function destroy($id) {
        $row = Model::findOrFail($id);
        $balance = ReceivableBalance::where('branch_id',$row->branch_id)
                                    ->where('project_id', $row->project_id)
                                    ->where('receivable_vendor_id', $row->receivable_vendor_id)
                                    ->first();


        if(!$row->is_paid) {
            $balance->amount -= $row->amount;
        }

        $row->delete();
        $balance->save();
        return redirect()->back()->with('f-msg', 'Data berhasil dihapus.');
    }
    public function changeIsPaid(Request $request, $id) {

        $row = Model::findOrFail($id);
        // $balance = ReceivableBalance::where('branch_id',$row->branch_id)
        //                             ->where('project_id', $row->project_id)
        //                             ->where('receivable_vendor_id', $row->receivable_vendor_id)
        //                             ->first();
        $balance = ReceivableBalance::firstOrNew([
            'branch_id' => $row->branch_id,
            'project_id' => $row->project_id,
            'receivable_vendor_id' => $row->receivable_vendor_id,
        ]);
        $balance->branch_id = $row->branch_id;
        $balance->project_id = $row->project_id;
        $balance->receivable_vendor_id = $row->receivable_vendor_id;
        if ($row->is_paid) {

            $row->is_paid = false;
            $amount = $balance->amount + $row->amount;
            $balance->amount = $amount;

            // dd($amount);
            $row->pay_date=null;

        }  else if (!$row->is_paid) {
            // dd($row->amount);
            $amount = $balance->amount - $row->amount;
            $balance->amount = $amount;

            $row->is_paid = true;
            $row->pay_date=$request->pay_date;
        }

        $balance->save();
        $row->save();
        return redirect()->back()->with('f-msg', 'Status order berhasil diubah.');
    }
    public function print($id) {
        $data = Model::findOrFail($id);

        $pdf = PDF::loadView('pdf.invoice-voucher', compact('data'));
        return $pdf->stream();
    }
    // Balance
    public function balanceIndex(Request $request) {
        $query = ReceivableBalance::select('*');
        if (!in_array(Auth::user()->role, self::$fullAccess))
        $query->where('branch_id', Auth::user()->branch_id);

        if($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if($request->project_id) {
            $query->where('project_id', $request->project_id);
        }
        if($request->receivable_vendor_id) {
            $query->where('receivable_vendor_id', $request->receivable_vendor_id);
        }

        $options = self::staticOptions();
        $datas = $query->paginate(40)->withQueryString();

        return view('pages.ReceivableBalance', compact('datas', 'options'));

    }

// Vendor
    public function vendorReceivable(Request $request) {
        $query = ReceivableVendor::select('*');
        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
        }

        if($request->receivable_vendor_id) {
            $query->where('receivable_vendor_id', $request->receivable_vendor_id);
        }


        if ($request->ajax()) {
            $datas = $query->get();

            return response()->json([
                'datas' => $datas,
            ]);
        }

        $datas = $query->paginate(40)->withQueryString();

        $branches = Branch::all();
        if (!in_array(Auth::user()->role, self::$fullAccess))
        $branches->where('branch_id', Auth::user()->branch_id);
        else
        $branches->where('branch_id', $request->branch_id);

        if ($branches->isNotEmpty()) {
            $branches = $branches->map(function ($branch) {
                return [
                    'text' => $branch->name,
                    'value' => $branch->id,
                ];
            });
        }

        $options = self::staticOptions();

        return view('pages.ReceivableVendorIndex', compact('datas', 'options'));

    }

    public function VendorStore(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:vendors,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'name' => ['required',
                        'string',
                        'max:255',
                        Rule::unique('vendors')->where(function ($query) use ($request) {
                            $query->where('branch_id', $request->branch_id);
                        }),
            ]
        ]);

        $row = ReceivableVendor::findOrNew($request->id);

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
            $row->branch_id = Auth::user()->branch_id;
            else
                $row->branch_id = $request->branch_id;
        }
        $row->branch_id = $request->branch_id;
        $row->name = $request->name;

        $row->save();

        return redirect()->route('receivable-vendor.index')->with('f-msg', 'Vendor berhasil disimpan.');
    }

    public function showVendor($id)
    {
        $data = ReceivableVendor::findOrFail($id);

        $branches = Branch::all();
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

        return view('pages.VendorReceivableDetail', compact('data', 'options'));
    }

    public function deleteVendor(Request $request) {

        DB::beginTransaction();
        try {
          ReceivableVendor::where('id', $request->id)->delete();

            DB::commit();
            return redirect()->back()->with('f-msg', 'Data Berhasil diHapus');

        }  catch (Error $e) {
            DB::rollBack();
            dd($e);
        }

    }
    // public function changeIsPaid($id)


}

