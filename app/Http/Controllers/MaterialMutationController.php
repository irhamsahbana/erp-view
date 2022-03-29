<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MaterialMutation as Model;
use App\Models\Branch;
use App\Models\MaterialBalance;
use Illuminate\Support\Facades\Auth;

class MaterialMutationController extends Controller
{
    public function __construct()
    {
        $this->middleware('has.access:owner', ['only' => ['changeIsOpen']]);
    }

    public function index(Request $request)
    {
        $query = Model::select('*');

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
        }

        if ($request->project_id)
            $query->where('project_id', $request->project_id);

        if ($request->material_id)
            $query->where('material_id', $request->material_id);

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
                $query->where('type', 2);
        }

        if ($request->date_start)
            $query->whereDate('created', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('created', '<=', new \DateTime($request->date_finish));

        $query->orderBy('created', 'desc');

        if (!in_array(Auth::user()->role, self::$fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        if ($request->ajax()) {
            $datas = $query->get();

            return response()->json([
                'datas' => $datas,
            ]);
        }

        $datas = $query->paginate(40)->withQueryString();
        $options = self::staticOptions();

        return view('pages.MaterialMutationIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:material_mutations,id'],
            'branch_id' => ['required_without:id', 'exists:branches,id'],
            'project_id' => ['required_without:id', 'exists:projects,id'],
            'material_id' => ['required_without:id', 'exists:materials,id'],
            'type' => ['required_without:id', 'in:in,out'],

            'material_price' => ['nullable', 'required_if:type,in', 'numeric'],
            'volume' => ['required', 'numeric'],
            'notes' => ['nullable'],
            'created' => ['required', 'date'],
        ]);

        $row = Model::findOrNew($request->id);

        if ($row->id && !$row->is_open)
            return redirect()->back()->withErrors(['messages' => 'Sudah ditutup.']);

        if (!$row->id) {
            $prefix = sprintf('%s/', $row->getTable());
            $postfix = sprintf('/%s.%s', date('m'), date('y'));
            $row->ref_no = $this->generateRefNo($row->getTable(), 4, $prefix, $postfix);

            if (in_array(Auth::user()->role, self::$fullAccess))
                $row->branch_id = $request->branch_id;
            else
                $row->branch_id = Auth::user()->branch_id;

            $row->project_id = $request->project_id;
            $row->material_id = $request->material_id;

            if ($request->type == 'in')
                $row->type = 1;
            else if ($request->type == 'out')
                $row->type = 2;
        }

        $row->volume = $request->volume;
        $row->created = $request->created;
        $row->notes = $request->notes;

        $balance = MaterialBalance::firstOrNew([
                                    'branch_id' => $row->branch_id,
                                    'project_id' => $row->project_id,
                                    'material_id' => $row->material_id
                                ]);

        $totalBalance = Model::where('branch_id', $row->branch_id)
                                ->where('project_id', $row->project_id)
                                ->where('material_id', $row->material_id)
                                ->get();

        $totalBalancePlusVolume = $totalBalance->where('type', Model::TYPE_IN)->sum('volume');
        $totalBalanceMinusVolume = $totalBalance->where('type', Model::TYPE_OUT)->sum('volume');
        $totalBalanceVolume = $totalBalancePlusVolume - $totalBalanceMinusVolume;

        $totalBalancePlusMaterialPrice = $totalBalance->where('type', Model::TYPE_IN)->sum('material_price');
        $totalBalanceMinusMaterialPrice = $totalBalance->where('type', Model::TYPE_OUT)->sum('material_price');
        $totalBalanceMaterialPrice = $totalBalancePlusMaterialPrice - $totalBalanceMinusMaterialPrice;

        if ($row->type == Model::TYPE_IN) {
            $row->material_price = $request->material_price;

            if (!$row->id) { // when create
                $balance->qty = $totalBalanceVolume + $row->volume;
                $balance->total = $totalBalanceMaterialPrice + $row->material_price;
            } else if ($row->id && //when update
                    ($row->volume != $row->getRawOriginal('volume') ||
                    $row->material_price != $row->getRawOriginal('material_price'))
                ) {
                $balance->qty = $totalBalanceVolume + $row->volume - $row->getRawOriginal('volume');
                $balance->total = $totalBalanceMaterialPrice + $row->material_price - $row->getRawOriginal('material_price');
            }
        } else if ($row->type == Model::TYPE_OUT) {
            if ($totalBalanceVolume < $row->volume)
                return redirect()->back()->withErrors(['messages' => 'Jumlah volume yang dikurangi melebihi jumlah stok.']);

            if (!$row->id) { // when create
                $balance->qty = $totalBalanceVolume - $row->volume;
                $row->material_price = $totalBalanceMaterialPrice / $totalBalanceVolume * $row->volume;
                $balance->total = $totalBalanceMaterialPrice - $row->material_price;
            } else if ($row->id && $row->volume != $row->getRawOriginal('volume')) { //when update
                $balance->qty = $totalBalanceVolume - $row->volume + $row->getRawOriginal('volume');
            }
        }

        if ($balance->qty < 0)
            return redirect()->back()->withErrors(['messages' => 'Jumlah volume kurang dari jumlah stok.']);

        // dd($balance->toArray(), $row->toArray());

        $balance->save();
        $row->save();

        return redirect()->back()->with('f-msg', 'Mutasi Material berhasil disimpan.');
    }

    // public function show($id)
    // {
    //     $data = Model::findOrFail($id);
    //     $options = self::staticOptions();

    //     return view('pages.MaterialMutationDetail', compact('data', 'options'));
    // }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);

        $balance = MaterialBalance::firstOrNew([
            'branch_id' => $row->branch_id,
            'project_id' => $row->project_id,
            'material_id' => $row->material_id
        ]);

        if ($row->type == Model::TYPE_IN) { //lakukan pengurangan terhadap saldo
            $balance->qty -= $row->volume;
            $balance->total -= $row->material_price;
        } else if ($row->type == Model::TYPE_OUT) { //lakukan penambahan terhadap saldo
            $balance->qty += $row->volume;
            $balance->total += $row->material_price;
        }

        if ($balance->total < 0)
            return redirect()->back()->withErrors(['messages' => 'Saldo harga kurang dari yang tersedia.']);

        if ($balance->qty < 0)
            return redirect()->back()->withErrors(['messages' => 'Jumlah volume kurang dari stok.']);

        $balance->save();
        $row->delete();

        return redirect()->back()->with('f-msg', 'Mutasi material berhasil dihapus.');
    }

    public function changeIsOpen($id)
    {
        $row = Model::findOrFail($id);
        $row->is_open = !$row->is_open;

        $row->save();

        return redirect()->back()->with('f-msg', 'Status berhasil diubah.');
    }

    public function balance(Request $request)
    {
        $query = MaterialBalance::select('*');

        if (!in_array(Auth::user()->role, self::$fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
        }

        if ($request->project_id)
            $query->where('project_id', $request->project_id);

        if ($request->material_id)
            $query->where('material_id', $request->material_id);

        $datas = $query->paginate(40)->withQueryString();
        $options = self::staticOptions();

        return view('pages.MaterialBalanceIndex', compact('datas', 'options'));
    }

    public static function staticOptions()
    {
        $branches = Branch::all();

        if (!in_array(Auth::user()->role, self::$fullAccess))
            $branches = $branches->where('id', Auth::user()->branch_id);

        if ($branches->isNotEmpty())
            $branches = $branches->map(function ($branch) {
                return [
                    'text' => $branch->name,
                    'value' => $branch->id,
                ];
            });

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

        return $options;
    }
}
