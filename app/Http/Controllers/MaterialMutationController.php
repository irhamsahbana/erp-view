<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MaterialMutation as Model;
use App\Models\Branch;
use App\Models\MaterialBalance;
use Illuminate\Support\Facades\Auth;

class MaterialMutationController extends Controller
{
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

        if ($request->project_id)
            $query->where('project_id', $request->project_id);

        if ($request->material_id)
            $query->where('material_id', $request->material_id);

        if ($request->driver_id)
            $query->where('driver_id', $request->driver_id);

        if ($request->is_open) {
            $isOpen = $request->is_open;

            if ($isOpen == 'open')
                $query->where('is_open', 1);
            else
                $query->where('is_open', 0);
        }

        if ($request->type) {
            $type = $request->type;

            if ($type == 'in')
                $query->where('type', 1);
            else if ($type == 'out')
                $query->where('type', 0);
        }

        if ($request->date_start)
            $query->whereDate('created', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('created', '<=', new \DateTime($request->date_finish));

        $query->orderBy('created', 'desc');

        if (!in_array(Auth::user()->role, $fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        $datas = $query->paginate(40)->withQueryString();

        $branches = Branch::all();

        if (!in_array(Auth::user()->role, $fullAccess)) {
            $branches = $branches->where('id', Auth::user()->branch_id);
        }

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

        $types = [
            ['text' => 'Masuk', 'value' => 'in'],
            ['text' => 'Keluar', 'value' => 'out'],
        ];

        $options = [
            'branches' => $branches,
            'status' => $status,
            'types' => $types,
        ];

        return view('pages.MaterialMutationIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:material_mutations,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'project_id' => ['required', 'exists:projects,id'],
            'material_id' => ['required', 'exists:materials,id'],
            'driver_id' => ['required', 'exists:drivers,id'],

            'type' => ['required', 'in:in,out'],
            'volume' => ['required', 'numeric'],
            'material_price' => ['required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'created' => ['required', 'date'],
        ]);

        $fullAccess = ['owner', 'admin'];

        $row = Model::findOrNew($request->id);

        if ($row->id && !$row->is_open)
            return redirect()->back()->withErrors(['messages' => 'Sudah ditutup.']);

        if (in_array(Auth::user()->role, $fullAccess))
            $row->branch_id = $request->branch_id;
        else
            $row->branch_id = Auth::user()->branch_id;

        $row->project_id = $request->project_id;
        $row->material_id = $request->material_id;
        $row->driver_id = $request->driver_id;

        $row->volume = $request->volume;
        $row->material_price = $request->material_price;
        $row->cost = $request->cost;
        $row->created = $request->created;

        $balance = MaterialBalance::firstOrNew([
                                'branch_id' => $row->branch_id,
                                'project_id' => $request->project_id,
                                'material_id' => $request->material_id
                            ]);

        if ($request->type == 'in') {
            $row->type = 1;

            if (!$balance->id) {
                $balance->qty = $request->volume;
                $balance->total = $request->material_price;
                $balance->unit_price = (float) $request->material_price / (float) $request->volume;
            } else {
                $balance->qty += $request->volume;
                $balance->total += $request->material_price;
                $balance->unit_price = $balance->total / $balance->qty;
            }

            $balance->save();
        } else {
            $row->type = 0;

            if ($balance->id) {
                if ($balance->qty >= $request->volume) {
                    $outPrice = $balance->total / $balance->qty * $request->volume;

                    if ($balance->qty == $request->volume)
                        $outPrice = $balance->total;

                    $row->material_price = $outPrice;

                    $newQty = $balance->qty - $request->volume;
                    $newTotal = $balance->total - $balance->unit_price * $request->volume;

                    if ($balance->qty == $request->volume)
                        $newTotal = 0;

                    $balance->qty = $newQty;
                    $balance->total = $newTotal;

                    $balance->save();
                } else {
                    return redirect()->back()->withErrors(['messages' => 'Saldo material kurang dari yang tersedia.']);
                }
            } else {
                return redirect()->back()->withErrors(['messages' => 'Saldo material tidak ada, lakukan penambahan terlebih dahulu.']);
            }
        }

        $row->save();

        return redirect()->back()->with('f-msg', 'Mutasi Material berhasil disimpan.');
    }

    public function show($id)
    {
        $fullAccess = ['owner', 'admin'];

        $data = Model::findOrFail($id);

        $branches = Branch::all();

        if (!in_array(Auth::user()->role, $fullAccess)) {
            $branches = $branches->where('id', Auth::user()->branch_id);
        }

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

        $types = [
            ['text' => 'Masuk', 'value' => 'in'],
            ['text' => 'Keluar', 'value' => 'out'],
        ];

        $options = [
            'branches' => $branches,
            'status' => $status,
            'types' => $types,
        ];

        return view('pages.MaterialMutationDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Mutasi material berhasil dihapus.');
    }

    public function changeIsOpen($id)
    {
        $hasAccess = ['owner'];

        if (!in_array(Auth::user()->role, $hasAccess))
            return redirect()->back()->withErrors(['messages' => 'Anda tidak memiliki akses.']);

        $row = Model::findOrFail($id);
        $row->is_open = !$row->is_open;

        $row->save();

        return redirect()->back()->with('f-msg', 'Status berhasil diubah.');
    }
}
