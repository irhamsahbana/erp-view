<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Material as Model;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Model::select('*');
        $query->orderBy('id', 'desc');

        if ($request->ajax()) {
            $datas = $query->get();

            return response()->json([
                'datas' => $datas,
            ]);
        }

        $datas = $query->paginate(40);

        return view('pages.MaterialIndex', compact('datas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:materials,id'],
            'name' => ['required', 'string', 'max:255', 'unique:materials,name,'. $request->id],
        ]);

        $row = Model::findOrNew($request->id);
        $row->name = $request->name;
        $row->save();

        return redirect()->back()->with('f-msg', 'Material berhasil disimpan.');
    }

    public function show(Request $request, $id)
    {
        $data = Model::findOrFail($id);

        if ($request->ajax()) {
            return response()->json([
                'data' => $data,
            ]);
        }

        return view('pages.MaterialDetail', compact('data'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Material berhasil dihapus.');
    }
}
