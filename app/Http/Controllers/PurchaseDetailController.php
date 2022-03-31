<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;

class PurchaseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_id' => ['required'],
            'branch_id' => ['required_without:id', 'exists:branches,id'],
            'name' => ['required'],
            'amount' => ['nullable', 'numeric'],
            'qty' => ['required', 'numeric'],
            'unit' => ['required'],
            'notes' => ['required', 'string', 'max:255']
        ]);

        if($request->amount){
            $amount = $request->amount;
        }else{
            $amount = 0;
        }
        $row = PurchaseDetail::findOrNew($request->id);

        $row->purchase_id = $request->purchase_id;
        $row->branch_id = $request->branch_id;
        $row->name = $request->name;
        $row->amount = $amount;
        $row->qty = $request->qty;
        $row->unit = $request->unit;
        $row->notes = $request->notes;
        $row->save();

        $purchase = Purchase::findOrFail($request->purchase_id);

        $purchase->total += $request->amount;
        $purchase->save();

        return redirect()->back()->with('f-msg', 'Detail Purchase berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseDetail  $purchaseDetail
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseDetail $purchaseDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseDetail  $purchaseDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseDetail $purchaseDetail)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $row = PurchaseDetail::findOrFail($id);
        $purchase = Purchase::findOrFail($row->purchase_id);

        $otherPurchasedetail = PurchaseDetail::where('purchase_id', $row->purchase_id)->where('id', '!=',$row->id);
        $otherPurchasedetail = $otherPurchasedetail->sum('amount');

        $row->amount = $request->amount;
        $total = $otherPurchasedetail + $row->amount;

        $purchase->total = $total;

        $purchase->save();
        $row->save();

        return redirect()->back()->with('f-msg', 'Total Harga Berhasil diubah');
    }

    public function destroy($id)
    {
        $row = PurchaseDetail::findOrFail($id);
        $row->delete();

        $purchase = Purchase::findOrFail($row->purchase_id);

        $purchase->total -= $row->amount;
        $purchase->save();
        return redirect()->back()->with('f-msg', 'Detail Pembelian berhasil dihapus.');
    }
}
