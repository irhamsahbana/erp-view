<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Branch;
use App\Models\PurchaseDetail;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('has.access:owner', ['only' => ['changeIsOpen']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Purchase::select('*');
        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
        }

        if ($request->vendor_id)
            $query->where('vendor_id', $request->vendor_id);


        if ($request->date_start)
            $query->whereDate('created', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('created', '<=', new \DateTime($request->date_finish));

        $query->orderBy('created', 'desc');

        if (!in_array(Auth::user()->role, self::$fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        if ($request->ajax()) {
            $datas = $query->get();

            return response()->json([
                'datas' => $datas,
            ]);
        }

        $datas = $query->paginate(40)->withQueryString();
        $options = self::staticOptions();

        return view('pages.PurchaseIndex', compact('datas', 'options'));
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
            'id' => ['nullable', 'exists:purchases,id'],
            'branch_id' => ['required_without:id', 'exists:branches,id'],
            'vendor_id' => ['required_without:id', 'exists:vendors,id'],
            'user' => ['required'],
            'created' => ['required', 'date'],
        ]);

        $row = Purchase::findOrNew($request->id);

        if (!$row->id) {
            $prefix = sprintf('%s/', $row->getTable());
            $postfix = sprintf('/%s.%s', date('m'), date('y'));
            $row->ref_no = $this->generateRefNo($row->getTable(), 4, $prefix, $postfix);

            if (in_array(Auth::user()->role, self::$fullAccess))
                $row->branch_id = $request->branch_id;
            else
                $row->branch_id = Auth::user()->branch_id;

            $row->vendor_id = $request->vendor_id;

        }

        $row->user = $request->user;
        $row->is_paid = false;
        $row->is_open = false;
        $row->total = 0;
        $row->created = $request->created;

        $row->save();

        return redirect(route('purchasing.index'))->with('f-msg', 'Purchase berhasil disimpan');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = PurchaseDetail::select('*')->where('purchase_id', $id);
        $purchase = Purchase::find($id);

        $datas = $row->paginate(40)->withQueryString();
        $options = self::staticOptions();

        return view('pages.PurchaseDetail' , compact('datas', 'options', 'purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = Purchase::findOrFail($id);
        $row->delete();
        return redirect()->back()->with('f-msg', 'Pembelian berhasil dihapus.');
    }

    public function changeIsOpen($id)
    {
        $row = Purchase::findOrFail($id);
        $row->is_open = !$row->is_open;

        $row->save();

        return redirect()->back()->with('f-msg', 'Status berhasil diubah.');
    }

    public function changeIsPaid($id)
    {
        $row = Purchase::findOrFail($id);
        $row->is_paid = !$row->is_paid;

        $row->save();

        return redirect()->back()->with('f-msg', 'Status Bayar berhasil diubah.');
    }

    public static function staticOptions()
    {
        $branches = Branch::all();
        $users = User::all();
        $vendors = Vendor::all();

        if (!in_array(Auth::user()->role, self::$fullAccess))
            $branches = $branches->where('id', Auth::user()->branch_id);

        if ($branches->isNotEmpty())
            $branches = $branches->map(function ($branch) {
                return [
                    'text' => $branch->name,
                    'value' => $branch->id,
                ];
            });

        if ($users->isNotEmpty()){
            $users = $users->map(function ($user){
                return [
                    'text' => $user->username,
                    'value' => $user->id
                ];
            });
        }

        if ($vendors->isNotEmpty()) {
            $vendors = $vendors->map(function ($vendor) {
                return [
                    'text' => $vendor->name,
                    'value' => $vendor->id
                ];
            });
        }

        $status = [
            ['text' => 'Open', 'value' => 'open'],
            ['text' => 'Close', 'value' => 'close'],
        ];

        $status_paid = [
            ['text' => 'Paid', 'value' => 'paid'],
            ['text' => 'Unpaid', 'value' => 'unpaid'],
        ];

        $options = [
            'branches' => $branches,
            'users' => $users,
            'vendors' => $vendors,
            'status' => $status,
            'status_paid' => $status_paid
        ];

        return $options;
    }
}
