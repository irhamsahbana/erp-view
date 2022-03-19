<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

use App\Models\Fuel as Model;
use App\Models\Branch;

class FuelController extends Controller
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

        if ($request->vehicle_id)
            $query->where('vehicle_id', $request->vehicle_id);

        if ($request->is_open) {
            $isOpen = $request->is_open;

            if ($isOpen == 'open')
                $query->where('is_open', 1);
            else if ($isOpen == 'close')
                $query->where('is_open', 0);
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

        $options = [
            'branches' => $branches,
            'status' => $status,
        ];

        return view('pages.FuelIndex', compact('datas', 'options'));
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
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $row = Model::findOrNew($request->id);

        if (!$row->id) {
            $prefix = sprintf('%s/', $row->getTable());
            $postfix = sprintf('/%s.%s', date('m'), date('y'));
            $row->ref_no = $this->generateRefNo($row->getTable(), 4, $prefix, $postfix);
        }

        $row->branch_id = $request->branch_id;
        $row->vehicle_id = $request->vehicle_id;
        $row->amount = $request->amount;
        $row->notes = $request->notes;
        $row->created = $request->created;

        if ($row->id && !$row->is_open)
            return redirect()->back()->withErrors(['messages' => 'Sudah ditutup.']);

        $row->save();

        return redirect()->back()->with('f-msg', 'Solar berhasil disimpan.');
    }

    public function show($id)
    {
        $fullAccess = ['owner', 'admin'];

        $data = Model::findOrFail($id);

        $branches = Branch::all();

        if (!in_array(Auth::user()->role, $fullAccess))
            $branches = $branches->where('id', Auth::user()->branch_id);

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

        return view('pages.FuelDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);

        if (!$row->is_open)
            return redirect()->back()->with('f-msg', 'Solar masih dibuka.');

        $row->delete();

        return redirect()->back()->with('f-msg', 'Solar berhasil dihapus.');
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

    public function print($id)
    {
        $fullAccess = ['owner', 'admin'];

        $data = Model::findOrFail($id);

        $pdf = PDF::loadView('pdf.invoice-fuel', compact('data'));
        return $pdf->stream();
    }
}
