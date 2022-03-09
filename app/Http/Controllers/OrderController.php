<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order as Model;
use App\Models\Branch;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $hasAccess = ['owner', 'admin', 'branch_head', 'cashier'];

        if (!in_array(Auth::user()->role, $hasAccess))
            return redirect()->back()->withErrors(['messages' => 'Anda tidak memilik akses.']);

        $fullAccess = ['owner', 'admin'];

        $query = Model::select('*');

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, $fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
        }

        if ($request->status)
            $query->where('status', $request->status);

        if ($request->is_open) {
            $isOpen = $request->is_open;

            if ($isOpen == 'open')
                $query->where('is_open', 1);
            else if ($isOpen == 'close')
                $query->where('is_open', 0);
        }

        if ($request->user_id)
            $query->where('user_id', $request->user_id);

        if ($request->date_start)
            $query->whereDate('created', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('created', '<=', new \DateTime($request->date_finish));

        $query->orderBy('created', 'desc');

        if (!in_array(Auth::user()->role, $fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        $datas = $query->paginate(40)->withQueryString();

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
            ['text' => 'Urgent', 'value' => 5],
        ];

        $options = [
            'branches' => $branches,
            'status' => $status,
            'statusOrder' => $statusOrder,
        ];

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
            $row->status = 1;

        $hasAccess = ['owner', 'admin', 'cashier'];

        if (!in_array(Auth::user()->role, $hasAccess))
            return redirect()->back()->withErrors(['messages' => 'Anda tidak memilik akses.']);

        if ($row->id && !$row->is_open)
            return redirect()->back()->withErrors(['messages' => 'Sudah ditutup.']);

        $row->save();

        return redirect()->back()->with('f-msg', 'Order berhasil disimpan.');
    }

    public function show($id)
    {
        $fullAccess = ['owner', 'admin'];

        $data = Model::findOrFail($id);

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
            ['text' => 'Urgent', 'value' => 5],
        ];

        $options = [
            'branches' => $branches,
            'status' => $status,
            'statusOrder' => $statusOrder,
        ];

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
}
