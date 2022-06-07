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



class BillController extends Controller
{
    //  Bill
    public function indexBill(Request $request) {

        $query = Bill::select("*");

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
            }

        if($request->bill_vendor_id)
            $query->where('bill_vendor_id', $request->bill_vendor_id);

        if($request->is_paid)
            $query->where('is_paid', $request->is_paid);


        if ($request->recive_date_start)
        $query->whereDate('recive_date', '>=', new \DateTime($request->recive_date_start));

        if ($request->recive_date_finish)
            $query->whereDate('recive_date', '<=', new \DateTime($request->recive_date_finish));

        if ($request->due_date_start)
        $query->whereDate('due_date', '>=', new \DateTime($request->due_date_start));

        if ($request->due_date_finish)
            $query->whereDate('due_date', '<=', new \DateTime($request->due_date_finish));
    // dd($query->get());

        $total = $query->where('is_paid', false)->sum('amount');
        $options = self::staticOptionBill();
        $datas = $query->paginate(40)->withQueryString();
        // dd($options["vendors"]);

        return view('pages.Bill', compact('datas', 'options'));

    }
    public function createBill() {
        // dd("Cek");
        $options = self::staticOptionBill();

        return view('pages.BillCreate', compact('options'));

    }

    public function addBill(Request $request) {


        // $balance = BillBalance::firstOrNew([
        //     'branch_id' => Auth::user()->branch_id,
        //     'bill_vendor_id'=> $request->new_bill_vendor_id
        // ]);
        // $balance->branch_id =  Auth::user()->branch_id;
        // $balance->bill_vendor_id = $request->new_bill_vendor_id;
        $row = Bill::findOrNew($request->id);
        // $balance->total += $request->amount;

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
        // $balance->save();
        return redirect()->route('bill.detail', $row->id)->with('success', 'Data berhasil ditambahkan');
    }

    public function deleteBill() {

    }
    public function addSubBill(Request $request) {
        $bill = "";

        $row=SubBill::findOrNew($request->id);
        $row = "";
    }

    public function detailBill($id) {
        $subBill = SubBill::select('*')->where('bill_id', $id);
        $bill = Bill::find($id);
        // $totalSub = 0;
        // $totalSub = $subBill->sum('total');
        $options = self::staticOptionBill();
        return view('pages.BillDetail', compact('options','subBill', 'bill'));
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
            ['text' => 'Sudah Bayar', 'value' => true],
            ['text' => 'Belum Bayar', 'value' => false],
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
