<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order as Model;
use App\Models\Branch;

class OrderController extends Controller
{
    private $fullAccess = ['owner', 'admin'];

    public function __construct()
    {
        $this->middleware('has.access:owner,admin,branch_head,cashier', ['only' => ['index']]);
        $this->middleware('has.access:owner,admin,cashier', ['only' => ['store']]);
        $this->middleware('has.access:owner,branch_head', ['only' => ['changeStatus']]);
        $this->middleware('has.access:owner', ['only' => ['changeIsOpen']]);
    }

    public function index(Request $request)
    {
        $query = Model::select('*');

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, $this->fullAccess))
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

        if ($request->user_id)
            $query->where('user_id', $request->user_id);

        if ($request->date_start)
            $query->whereDate('created', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('created', '<=', new \DateTime($request->date_finish));

        $query->orderBy('created', 'desc');

        if (!in_array(Auth::user()->role, $this->fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        $datas = $query->paginate(40)->withQueryString();

        $options = self::staticOptions();

        return view('pages.OrderIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:orders,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'amount' => ['required', 'numeric'],
            'created' => ['required', 'date'],
            'is_open' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $row = Model::findOrNew($request->id);
        $row->branch_id = $request->branch_id;
        $row->user_id = Auth::id();
        $row->amount = $request->amount;
        $row->created = $request->created;
        $row->notes = $request->notes;

        if (!$row->id)
            $row->status = Model::STATUS_ORDER_WAITING;

        if ($row->id && Model::IS_OPEN_CLOSE)
            return redirect()->back()->withErrors(['messages' => 'Sudah ditutup.']);

        $row->save();

        return redirect()->back()->with('f-msg', 'Order berhasil disimpan.');
    }

    public function show(Request $request, $id)
    {
        $data = new Model();

        $data = $data
                    ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                    ->leftJoin('branches', 'orders.branch_id', '=', 'branches.id')
                    ->select('orders.*', 'users.username', 'branches.name as branch_name')
                    ->where('orders.id', $id)->first();

        if (!$data)
            abort(404);

        if ($request->ajax())
            return response()->json($data);

        $options = self::staticOptions();

        return view('pages.OrderDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Order berhasil dihapus.');
    }

    public function changeIsOpen($id)
    {
        $row = Model::findOrFail($id);
        $row->is_open = !$row->is_open;

        $row->save();

        return redirect()->back()->with('f-msg', 'Status berhasil diubah.');
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'id' => ['required', 'exists:orders,id'],
            'status' => ['required', 'numeric', 'in:1,2,3,4'],
        ]);

        $row = Model::findOrFail($id);

        if ($row->is_open == Model::IS_OPEN_CLOSE)
            return redirect()->back()->withErrors(['messages' => 'Sudah ditutup.']);

        $row->status = $request->status;
        if ($request->status == Model::STATUS_ORDER_ACCEPTED || $request->status == Model::STATUS_ORDER_REJECTED)
            $row->is_open = Model::IS_OPEN_CLOSE;

        $row->save();

        return redirect()->back()->with('f-msg', 'Status order berhasil diubah.');
    }

    public static function staticOptions()
    {
        $fullAccess = ['owner', 'admin'];
        $branches = Branch::all();

        if (!in_array(Auth::user()->role, $fullAccess))
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

        $statusOrder = [
            ['text' => 'Waiting', 'value' => 1],
            ['text' => 'Accepted', 'value' => 2],
            ['text' => 'Rejected', 'value' => 3],
            ['text' => 'Hold', 'value' => 4],
        ];

        $options = [
            'branches' => $branches,
            'status' => $status,
            'statusOrder' => $statusOrder,
        ];

        return $options;
    }
}
