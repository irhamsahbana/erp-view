<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project as Model;
use App\Models\Branch;

class ProjectController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $query = Model::select('*');

        if ($request->branch_id)
            $query->where('branch_id', $request->branch_id);

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

        return view('pages.ProjectIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:projects,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $row = Model::findOrNew($request->id);
        $row->branch_id = $request->branch_id;
        $row->name = $request->name;

        $row->save();

        return redirect()->back()->with('f-msg', 'Proyek berhasil disimpan.');
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

        return view('pages.ProjectDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Proyek berhasil dihapus.');
    }
}
