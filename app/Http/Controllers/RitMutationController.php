<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\RitMutation as Model;
use App\Models\RitBalance;
use App\Models\Branch;
use App\Models\MaterialMutation;

class RitMutationController extends Controller
{
    public function __construct()
    {
        $this->middleware('has.access:owner,admin,branch_head,cashier', ['only' => ['index']]);
        $this->middleware('has.access:owner,admin,cashier', ['only' => ['store']]);
        $this->middleware('has.access:owner,admin', ['only' => ['destroy']]);
        $this->middleware('has.access:owner', ['only' => ['changeIsOpen']]);
    }

    public function index(Request $request)
    {
        $query = Model::select('*');

        if ($request->branch_id)
            $query->where('branch_id', $request->branch_id);

        if ($request->project_id)
            $query->where('project_id', $request->project_id);

        if ($request->driver_id)
            $query->where('driver_id', $request->driver_id);

        if ($request->material_mutation_id)
            $query->where('material_mutation_id', $request->material_mutation_id);

        if ($request->is_open) {
            $isOpen = $request->is_open;

            if ($isOpen == 'open')
                $query->where('is_open', 1);
            else
                $query->where('is_open', 0);
        }

        if ($request->transaction_type)
            $query->where('transaction_type', $request->transaction_type);

        if ($request->date_start)
            $query->whereDate('created', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('created', '<=', new \DateTime($request->date_finish));

        $query->orderBy('created', 'desc');

        if (!in_array(Auth::user()->role, self::$fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        $datas = $query->paginate(40)->withQueryString();

        $options = self::staticOptions();

        return view('pages.RitMutationIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:rit_mutations,id'],
            'branch_id' => ['required_without:id', 'exists:branches,id'],
            'project_id' => ['required_without:id', 'exists:projects,id'],
            'driver_id' => ['required_without:id', 'exists:drivers,id'],
            'material_mutation_id' => ['required_without:id', 'exists:material_mutations,id'],

            // 'transaction_type' => ['required_without:id', 'numeric'],
            'amount' => ['required', 'numeric'],
            'notes' => ['required', 'string', 'max:255'],
            // 'created' => ['required', 'date'],
        ]);

        $row = Model::findOrNew($request->id);

        if (!$row->id) {
            $prefix = sprintf('%s/', $row->getTable());
            $postfix = sprintf('/%s.%s', date('m'), date('y'));
            $row->ref_no = $this->generateRefNo($row->getTable(), 4, $prefix, $postfix);
        }

        if ($row->id && !$row->is_open)
            return redirect()->back()->withErrors(['messages' => 'Sudah ditutup.']);

        if (!$row->id) {
            if (in_array(Auth::user()->role, self::$fullAccess))
                $row->branch_id = $request->branch_id;
            else
                $row->branch_id = Auth::user()->branch_id;

            $row->project_id = $request->project_id;
            $row->driver_id = $request->driver_id;
            $row->material_mutation_id = $request->material_mutation_id;
        }
        $created = MaterialMutation::findOrFail($request->material_mutation_id);

        // dd($created);
        $row->created = $created->created;
        $row->amount = $request->amount;
        $row->notes = $request->notes;

        $balance = RitBalance::firstOrNew([
                                'branch_id' => $row->branch_id,
                                'project_id' => $row->project_id,
                                'driver_id' => $row->driver_id,
                                'material_mutation_id' => $row->material_mutation_id,
                            ]);

        // $totalBalance = Model::where('branch_id', $row->branch_id)
        //                     ->where('project_id', $row->project_id)
        //                     ->where('driver_id', $row->driver_id)
        //                     ->where('material_mutation_id', $row->material_mutation_id)
        //                     ->get();

        // $totalBalance = $totalBalance->where('is_paid', false)->sum('amount');
        // dd($totalBalance);
        // $totalBalancePlus = $totalBalance->where('transaction_type', Model::TRANSACTION_TYPE_ADD)->sum('amount');
        // $totalBalanceMinus = $totalBalance->where('transaction_type', Model::TRANSACTION_TYPE_SUBTRACT)->sum('amount');
        // $totalBalance = $totalBalancePlus - $totalBalanceMinus;


        // if ($row->transaction_type == Model::TRANSACTION_TYPE_ADD) {
        if (!$row->id)
            $balance->total += $row->amount;
        else if ($row->id && $row->getRawOriginal('amount') != $row->amount)
            $balance->total = $balance->total + $row->amount - $row->getRawOriginal('amount');

        $row->save();
        $balance->save();
        // } else if ($row->transaction_type == Model::TRANSACTION_TYPE_SUBTRACT) {
        if ($balance->total < $row->amount)
            return redirect()->back()->withErrors(['messages' => 'Saldo hutang ritase kurang dari yang tersedia.']);

        if (!$row->id)
            $balance->total = $balance->total - $row->amount;
        else if ($row->id && $row->getRawOriginal('amount') != $row->amount)
            $balance->total = $balance->total - $row->amount + $row->getRawOriginal('amount');

        if ($balance->total < 0)
            return redirect()->back()->withErrors(['messages' => 'Saldo hutang ritase kurang dari yang tersedia.']);

        $row->save();
        $balance->save();
        // }

        return redirect()->back()->with('f-msg', 'Mutasi hutang ritase berhasil disimpan.');
    }

    public function show($id)
    {
        $data = Model::findOrFail($id);
        $options = self::staticOptions();

        return view('pages.RitMutationDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);

        $balance = RitBalance::firstOrNew([
            'branch_id' => $row->branch_id,
            'driver_id' => $row->driver_id,
            'material_mutation_id' => $row->material_mutation_id,
        ]);

        if ($row->transaction_type == Model::TRANSACTION_TYPE_ADD) {
            $balance->total -= $row->amount;
        } else if ($row->transaction_type == Model::TRANSACTION_TYPE_SUBTRACT) {
            $balance->total += $row->amount;
        }

        if ($balance->total < 0)
            return redirect()->back()->withErrors(['messages' => 'Saldo hutang ritase kurang dari yang tersedia.']);

        $balance->save();

        $row->delete();

        return redirect()->back()->with('f-msg', 'Mutasi hutang ritase berhasil dihapus.');
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
        $query = RitBalance::select('*');

        if ($request->branch_id)
            $query->where('branch_id', $request->branch_id);

        if ($request->project_id)
            $query->where('project_id', $request->project_id);

        if ($request->driver_id)
            $query->where('driver_id', $request->driver_id);

        if ($request->material_mutation_id)
            $query->where('material_mutation_id', $request->material_mutation_id);

        if (!in_array(Auth::user()->role, self::$fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        $datas = $query->paginate(40)->withQueryString();
        $options = self::staticOptions();

        return view('pages.RitBalanceIndex', compact('datas', 'options'));
    }

    public function print($id)
    {
        $data = Model::findOrFail($id);

        $pdf = PDF::loadView('pdf.invoice-debt-mutation', compact('data'));
        return $pdf->stream();
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

        $transactionTypes = [
            ['text' => 'Penambahan', 'value' => 1],
            ['text' => 'Pegurangan', 'value' => 2],
        ];

        $options = [
            'branches' => $branches,
            'status' => $status,
            'transactionTypes' => $transactionTypes
        ];

        return $options;
    }

    public function changeIsPaid($id)
    {
        $row = Model::findOrFail($id);
        $row->is_paid = !$row->is_paid;


        $row->save();

        $balance = RitBalance::firstOrNew([
            'branch_id' => $row->branch_id,
            'project_id' => $row->project_id,
            'driver_id' => $row->driver_id,
            'material_mutation_id' => $row->material_mutation_id,
        ]);

        if($row->is_paid == true){
            $balance->total -= $row->amount;
        }else{
            $balance->total += $row->amount;
        }

        $balance->save();

        return redirect()->back()->with('f-msg', 'Status Pembayaran berhasil diubah.');
    }
}