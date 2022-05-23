<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;

use App\Models\Voucher as Model;
use App\Models\Branch;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('has.access:owner', ['only' => ['changeIsOpen']]);
    }

    public function index(Request $request)
    {

        $totalCost = 0;
        $totalIncome = 0;
        $query = Model::select('*');
        $query2 = Model::select('*')->get();
        // dd(Carbon::createFromFormat('Y-m-d', $item->created)->month);
        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
                $query2->where('branch_id', Auth::user()->branch_id);
            else
                $query2->where('branch_id', $request->branch_id);
        }
        foreach($query2 as $item) {
            if($item->type == 1) {

            $totalIncome += $item->amount;

            }  else if ($item->type == 2) {
                $totalCost += $item->amount;
             }
        }
        $totalCash = $totalIncome - $totalCost;


        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
        }

        if ($request->status)
            $query->where('status', $request->status);

        if ($request->is_open) {
            $isOpen = $request->is_open;

            if ($isOpen == 'open')
                $query->where('is_open', Model::IS_OPEN_OPEN);
            else if ($isOpen == 'close')
                $query->where('is_open', Model::IS_OPEN_CLOSE);
        }

        if ($request->type)
            $query->where('type', $request->type);

        if ($request->user_id)
            $query->where('user_id', $request->user_id);

        if ($request->date_start)
            $query->whereDate('created', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('created', '<=', new \DateTime($request->date_finish));

        if($request->keyword) {
         $list = explode(' ', $request->keyword);
         $list = array_map('trim', $list);

         $query->where(function($query) use ($list) {
             foreach($list as $x) {
                 $pattern = '%' . $x . '%';
                 $query->orWhere('notes', 'like', $pattern);
                 $query->orWhere('amount', 'like', $pattern);

             }
         });
        }
        $query->orderBy('created', 'desc');
        // dd($query);
        if (!in_array(Auth::user()->role, self::$fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        $datas = $query->paginate(40)->withQueryString();
        $options = self::staticOptions();

        return view('pages.VoucherIndex', compact('datas', 'options', 'totalIncome', 'totalCost', 'totalCash'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:vouchers,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'order_id' => [
                Rule::requiredIf($request->type == Model::TYPE_VOUCHER_EXPENSE && $request->status == Model::STATUS_VOUCHER_BY_PLANNING),
                'exists:orders,id'
            ],

            'type' => ['required', 'numeric', 'in:1,2'],
            'status' => [
                'nullable',
                Rule::requiredIf($request->type == Model::TYPE_VOUCHER_EXPENSE),
                'numeric',
                 'in:1,2'
            ],
            'amount' => ['required', 'numeric'],
            'notes' => ['required', 'string'],
            'is_open' => ['nullable', 'boolean'],
            'created' => ['required', 'date'],
        ]);

        $row = Model::findOrNew($request->id);

        if (!$row->id) {
            $prefix = sprintf('%s/', $row->getTable());
            $postfix = sprintf('/%s.%s', date('m'), date('y'));
            $row->ref_no = $this->generateRefNo($row->getTable(), 4, $prefix, $postfix);
        }

        $row->branch_id = $request->branch_id;
        $row->user_id = Auth::id();

        $row->type = $request->type;
        $row->amount = $request->amount;
        $row->notes = $request->notes;
        $row->created = $request->created;

        if ($row->type == Model::TYPE_VOUCHER_EXPENSE) {
            $row->order_id = $request->order_id;
            $row->status = $request->status;
        }

        if ($row->id && $row->is_open === Model::IS_OPEN_CLOSE)
            return redirect()->back()->withErrors(['messages' => 'Sudah ditutup.']);

        $row->save();

        return redirect()->back()->with('f-msg', 'Voucher berhasil disimpan.');
    }

    public function show(Request $request, $id)
    {
        $data = Model::findOrFail($id);
        $options = self::staticOptions();

        return view('pages.VoucherDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Voucher berhasil dihapus.');
    }

    public function changeIsOpen($id)
    {
        $row = Model::findOrFail($id);
        $row->is_open = !$row->is_open;

        $row->save();

        return redirect()->back()->with('f-msg', 'Status berhasil diubah.');
    }

    public function print($id)
    {
        $fullAccess = ['owner', 'admin'];

        $data = Model::findOrFail($id);

        $pdf = PDF::loadView('pdf.invoice-voucher', compact('data'));
        return $pdf->stream();
    }

    public function closeVoucer() {
        $row = Model::where("is_open",true)->get();
        foreach($row as $data) {
            $data->is_open = false;
            $data->save();
        };

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

        $status = [
            ['text' => 'Open', 'value' => 'open'],
            ['text' => 'Close', 'value' => 'close'],
        ];

        $statusVoucher = [
            ['text' => 'Urgent', 'value' => Model::STATUS_VOUCHER_URGENT],
            ['text' => 'By Planning', 'value' => Model::STATUS_VOUCHER_BY_PLANNING],
        ];

        $types = [
            ['text' => 'Pemasukan', 'value' => Model::TYPE_VOUCHER_INCOME],
            ['text' => 'Pengeluaran', 'value' => Model::TYPE_VOUCHER_EXPENSE],
        ];

        $options = [
            'branches' => $branches,
            'status' => $status,
            'statusVoucher' => $statusVoucher,
            'types' => $types,
        ];

        return $options;
    }
}
