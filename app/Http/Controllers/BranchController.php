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
            'id' => ['nullable'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $row = Model::findOrNew($request->id);
        $row->name = $request->name;
        $row->save();

        return redirect()->back()->with('success', 'Cabang berhasil disimpan');
    }
}
