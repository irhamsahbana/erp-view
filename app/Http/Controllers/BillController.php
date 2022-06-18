<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BillBalance;
use App\Models\SubBill;
use App\Models\BillVendor;
use App\Models\Branch;

use Illuminate\Support\Facades\DB;


class BillController extends Controller
{
    //  Bill
    public function indexBill(Request $request) {

        $query = Bill::select("*");
        $date = Carbon::now()->format('Y-m-d');

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
            }

        if($request->bill_vendor_id)
            $query->where('bill_vendor_id', $request->bill_vendor_id);

        if($request->is_paid) {
            if($request->is_paid == 3) {
                $query->where('is_paid', true);
            }  else if ($request->is_paid == 2) {
                $query->where('is_paid', false);
                            }
        }


        if ($request->recive_date_start)
        $query->whereDate('recive_date', '>=', new \DateTime($request->recive_date_start));

        if ($request->recive_date_finish)
            $query->whereDate('recive_date', '<=', new \DateTime($request->recive_date_finish));

        if ($request->due_date_start)
        $query->whereDate('due_date', '>=', new \DateTime($request->due_date_start));

        if ($request->due_date_finish)
            $query->whereDate('due_date', '<=', new \DateTime($request->due_date_finish));
    // dd($query->get());

    $query->orderBy('recive_date', 'desc')->orderBy('id','desc');
    $datas = $query->paginate(40)->withQueryString();
    $total_bill = $query->sum('amount');
    $total_balance = $query->where('is_paid', false)->sum('amount');
    $total_paid = $query->where('is_paid', true)->sum('amount');
    $total_due_date = $query->where('is_paid', false)->where('due_date', '<=', $date)->sum('amount');

    $options = self::staticOptionBill();
    // $total_is
        // dd($options["vendors"]);

        return view('pages.Bill', compact('datas',"total_balance",'date','total_bill','total_due_date', 'options'));

    }
    public function createBill() {
        // dd("Cek");
        $options = self::staticOptionBill();

        return view('pages.BillCreate', compact('options'));

    }

    public function addBill(Request $request) {


        $balance = BillBalance::firstOrNew([
            'branch_id' => Auth::user()->branch_id,
            'bill_vendor_id'=> $request->bill_vendor_id
        ]);
        $balance->branch_id =  Auth::user()->branch_id;
        $balance->bill_vendor_id = $request->bill_vendor_id;
        $row = Bill::findOrNew($request->id);
        $balance->total += 0;

        if(!$request->id) {
            $prefix = sprintf('%s/', $row->getTable());
            $postfix = sprintf('/%s.%s', date('m'), date('y'));
            $row->ref_no = $this->generateRefNo($row->getTable(), 4, $prefix, $postfix);
        }

        $row->bill_vendor_id = $request->bill_vendor_id;
        $row->recive_date = $request->recive_date;
        $row->branch_id = Auth::user()->branch_id;
        $row->amount = 0;
        $row->notes = $request->notes;
        $row->due_date = $request->due_date;

        $row->save();
        $balance->save();
        return redirect()->route('bill.detail', $row->id)->with('success', 'Data berhasil ditambahkan');
    }

    public function deleteBill($id) {
        $row = Bill::findOrNew($id);
        $balance = BillBalance::where('branch_id',$row->branch_id)
                    ->where('bill_vendor_id', $row->bill_vendor_id)
                    ->first();
        $sub = SubBill::where('bill_id', $id);


        DB::beginTransaction();
        try {
            $sub->delete();
            if(!$row->is_paid) {
                $balance->total -= $row->amount;
            }
            $balance->save();
            $row->delete();
            DB::commit();
            return redirect()->back()->with('f-msg', 'Data Berhasil diHapus');
        } catch(Error $e) {
            DB::rollBack();
            dd($e);
        }

        return redirect()->back()->with('f-msg', 'Data berhasil dihapus.');
    }
    public function addSubBill(Request $request) {
        // dd("yeess");
        $balance = BillBalance::where('branch_id',$request->branch_id)
                    ->where('bill_vendor_id', $request->bill_vendor_id)
                    ->first();

        $bill = Bill::where('id', $request->bill_id)->first();
        $total = $request->quantity * $request->unit_price;
        $bill->amount += $total;
        $balance->total += $total;

        $row=SubBill::findOrNew($request->id);
        $row->bill_item_id = $request->bill_item_id;
        $row->unit = $request->unit;
        $row->bill_id = $request->bill_id;
        $row->bill_vendor_id = $request->bill_vendor_id;
        $row->quantity = $request->quantity;
        $row->unit_price = $request->unit_price;
        $row->total = $total;

        $bill->save();
        $row->save();
        $balance->save();
         return redirect()->back()->with('f-msg', 'Status berhasil diubah.');

    }

    public function deleteSubBill($id) {
        // dd("tes");
        $row = SubBill::find($id);
        // dd($row->id);
        $bill = Bill::where('id', $row->bill_id)->first();

        $balance = BillBalance::where('branch_id',$bill->branch_id)
                    ->where('bill_vendor_id', $row->bill_vendor_id)
                    ->first();
        // dd($row);
        // dd($row->branch_id, $row->bill_vendor_id);
        DB::beginTransaction();
        try {
            if(!$bill->is_paid) {
                $balance->total -= $row->total;
            }
            $bill->amount -= $row->total;
            $bill->save();
            $balance->save();
            $row->delete();
            DB::commit();
            return redirect()->back()->with('f-msg', 'Data Berhasil diHapus');
        } catch(Error $e) {
            DB::rollBack();
            dd($e);
        }


    }

    public function detailBill($id) {
        $subBill = SubBill::where('bill_id', $id)->get();
        $bill = Bill::find($id);

        $options = self::staticOptionBill();
        return view('pages.BillDetail', compact('options','subBill', 'bill'));
    }

    public function changeIsPaid(Request $request, $id) {
        // dd("oiii");

        $row = Bill::findOrFail($id);
        // dd($row);
        $balance = BillBalance::where('branch_id',$row->branch_id)
        ->where('bill_vendor_id', $row->bill_vendor_id)
        ->first();

        // dd($balance);
        $balance->branch_id = $row->branch_id;
        $balance->bill_vendor_id = $row->bill_vendor_id;

        if($row->is_paid) {
            $row->is_paid = false;
            $balance->total += $row->amount;
            $row->pay_date=null;

        } else if(!$row->is_paid) {
            $row->is_paid = true;
            $row->pay_date=$request->pay_date;
            $balance->total -= $row->amount;

        }

        $balance->save();
        $row->save();
        return redirect()->back()->with('f-msg', 'Status berhasil diubah.');
    }

    public function staticOptionBill() {
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

        $status = [
            ['text' => 'Belum Bayar', 'value' => 2],
            ['text' => 'Sudah Bayar', 'value' => 3],
        ];

        $vendors = BillVendor::all();
        if ($vendors->isNotEmpty()) {
        $vendors = $vendors->map(function ($vendor) {
            return [
                'text' => $vendor->name,
                'value' => $vendor->id,
            ];
        });
        }
        $items = BillItem::all();
        if ($items->isNotEmpty()) {
        $items = $items->map(function ($item) {
            return [
                'text' => $item->name,
                'value' => $item->id,
            ];
        });
        }

        $options = [
            'branches' => $branches,
            'vendors' => $vendors,
            'items' => $items,
            'status' => $status,
        ];
        return $options;
    }


    // BillVendor
    public function indexVendor() {
        $query = BillVendor::select("*");
        $datas = $query->paginate(40)->withQueryString();
        return view('pages.BillVendor', compact('datas'));

        // return view('pages.ReceivableVendorIndex', compact('datas', 'options'));


    }
    public function addVendor(Request $request) {
        $request->validate([
            'name' => ['required']
        ]);

        $row = BillVendor::findOrNew($request->id);
        $row->name = $request->name;
        $row->save();

        return redirect()->back()->with('f-msg', 'Data berhasil disimpan.');
    }

    public function deleteVendor($id) {

        $row = BillVendor::findOrFail($id);
        $row->delete();
        return redirect()->back()->with('f-msg', 'Data berhasil Dihapus.');


    }

    // item
    public function indexItem() {

      $query = BillItem::select("*");
      $datas = $query->paginate(40)->withQueryString();
      return view('pages.BillItem', compact('datas'));

    }
    public function addItem(Request $request) {

        $request->validate([
            'name' => ['required']
        ]);

        $row = BillItem::findOrNew($request->id);
        $row->name = $request->name;
        $row->save();

        return redirect()->back()->with('f-msg', 'Data berhasil disimpan.');

    }

    public function deleteItem($id) {
        $row = BillItem::findOrFail($id);
        $row->delete();
        return redirect()->back()->with('f-msg', 'Data berhasil Dihapus.');
    }
    // balance
    public function indexBalance(Request $request) {
        $query = BillBalance::select("*");

        if($request->bill_vendor_id)
        $query->where('bill_vendor_id', $request->bill_vendor_id);


        $options = self::staticOptionBill();
        $datas = $query->paginate(40)->withQueryString();

        return view('pages.BillBalance', compact('datas', 'options'));

    }

}
