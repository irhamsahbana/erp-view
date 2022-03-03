<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Driver as Model;
use App\Models\Branch;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Model::select('*');

        if ($request->branch_id)
            $query->where('branch_id', $request->branch_id);

        $query->orderBy('id', 'desc');

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

        return view('Pages.DriverIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:drivers,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $row = Model::findOrNew($request->id);
        $row->branch_id = $request->branch_id;
        $row->name = $request->name;

        $row->save();

        return redirect()->back()->with('f-msg', 'Pengendara berhasil disimpan.');
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

        return view('Pages.DriverDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Pengendara berhasil dihapus.');
    }
}
