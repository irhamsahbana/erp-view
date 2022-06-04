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



class BillController extends Controller
{
    //  Bill
    public function indexBill() {
        dd("tes");
        $query = Bill::select("*");
        dd($query);
        // return

    }
    public function addBill() {

    }

    public function deleteBill() {

    }

    public function staticOptionBill() {

    }
    // BillVendor
    public function indexVendor() {
        $query = BillVendor::select("*");

        // return view('pages.ReceivableVendorIndex', compact('datas', 'options'));


    }
    public function addVendor() {

    }

    public function deleteVendor() {

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

    public function deleteItem() {

    }
    // balance
    public function indexBalance() {
        $query = Bill::select("*");

    }

}
