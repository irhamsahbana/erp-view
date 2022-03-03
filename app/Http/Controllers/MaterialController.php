<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Material as Model;

class MaterialController extends Controller
{
    public function index()
    {
        $query = Model::select('*');
        $query->orderBy('id', 'desc');

        $datas = $query->paginate(40);

        return view('Pages.MaterialIndex', compact('datas'));
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

    public function show($id)
    {
        $data = Model::findOrFail($id);

        return view('Pages.MaterialDetail', compact('data'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Material berhasil dihapus.');
    }
}
