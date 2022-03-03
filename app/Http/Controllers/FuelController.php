<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Fuel as Model;
use App\Models\Branch;

class FuelController extends Controller
{
    public function index(Request $request)
    {
        $query = Model::select('*');

        if ($request->branch_id)
            $query->where('branch_id', $request->branch_id);

        if ($request->vehicle_id)
            $query->where('vehicle_id', $request->vehicle_id);

        if ($request->is_open) {
            $isOpen = $request->is_open;

            if ($isOpen == 'open')
                $query->where('is_open', 1);
            else
                $query->where('is_open', 0);
        }

        if ($request->date_start)
            $query->whereDate('created', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('created', '<=', new \DateTime($request->date_start));

        $query->orderBy('created', 'desc');

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

        $status = [
            ['text' => 'Open', 'value' => 'open'],
            ['text' => 'Close', 'value' => 'close'],
        ];

        $options = [
            'branches' => $branches,
            'status' => $status,
        ];

        return view('Pages.FuelIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:fuels,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'amount' => ['required', 'numeric'],
            'created' => ['required', 'date'],
            'is_open' => ['nullable', 'boolean'],
        ]);

        $row = Model::findOrNew($request->id);
        $row->branch_id = $request->branch_id;
        $row->vehicle_id = $request->vehicle_id;
        $row->amount = $request->amount;
        $row->created = $request->created;

        $row->save();

        return redirect()->back()->with('f-msg', 'Solar berhasil disimpan.');
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

        return view('Pages.ProjectDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Solar berhasil dihapus.');
    }

    public function changeIsOpen($id)
    {
        $row = Model::findOrFail($id);
        $row->is_open = !$row->is_open;

        $row->save();

        return redirect()->back()->with('f-msg', 'Status berhasil diubah.');
    }
}
