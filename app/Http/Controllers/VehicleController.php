<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Vehicle as Model;
use App\Models\Branch;

class VehicleController extends Controller
{
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

        return view('pages.VehicleIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:vehicles,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'license_plate' => ['required', 'string', 'max:255', 'unique:vehicles,license_plate,' . $request->id],
        ]);

        $row = Model::findOrNew($request->id);
        $row->branch_id = $request->branch_id;
        $row->license_plate = $request->license_plate;

        $row->save();

        return redirect()->back()->with('f-msg', 'Kendaraan berhasil disimpan.');
    }

    public function show($id)
    {
        $data = Model::findOrFail($id);
        $options = self::staticOptions();

        return view('pages.VehicleDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Kendaraan berhasil dihapus.');
    }

    public static function staticOptions()
    {
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

        return $options;
    }
}
