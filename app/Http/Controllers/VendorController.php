<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Vendor as Model;
use App\Models\Branch;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('has.access:owner,admin,branch_head,accountant,cashier', ['only' => ['index', 'store']]);
        $this->middleware('has.access:owner,admin', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $fullAccess = ['owner', 'admin'];

        $query = Model::select('*');

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, $fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
        }

        if ($request->ajax()) {
            $datas = $query->get();

            return response()->json([
                'datas' => $datas,
            ]);
        }

        $datas = $query->paginate(40)->withQueryString();

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

        return view('pages.VendorIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $fullAccess = ['owner', 'admin'];

        $request->validate([
            'id' => ['nullable', 'exists:vendors,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $row = Model::findOrNew($request->id);

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, $fullAccess))
            $row->branch_id = Auth::user()->branch_id;
            else
                $row->branch_id = $request->branch_id;
        }
        $row->branch_id = $request->branch_id;
        $row->name = $request->name;

        $row->save();

        return redirect()->back()->with('f-msg', 'Vendor berhasil disimpan.');
    }

    public function show($id)
    {
        $data = Model::findOrFail($id);

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

        return view('pages.VendorDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $hasAccess = ['owner'];

        if (!in_array(Auth::user()->role, $hasAccess))
            return redirect()->back()->withErrors(['messages' => 'Anda tidak memiliki akses.']);

        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Vendor berhasil dihapus.');
    }
}
