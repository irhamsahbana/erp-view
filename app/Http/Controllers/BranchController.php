<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Branch as Model;

class BranchController extends Controller
{
    public function index()
    {
        $datas = Model::all();

        return view('Pages.BranchIndex', compact('datas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $row = Model::findOrNew($request->id);
        $row->name = $request->name;
        $row->save();

        return redirect()->back()->with('f-msg', 'Cabang berhasil disimpan.');
    }

    public function show($id)
    {
        $data = Model::findOrFail($id);

        return view('Pages.BranchDetail', compact('data'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Cabang berhasil dihapus.');
    }
}
