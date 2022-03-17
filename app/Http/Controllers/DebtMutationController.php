<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Repositories\DebtMutation;

use App\Models\DebtMutation as Model;
use App\Models\Branch;
use App\Models\DebtBalance;
use App\View\Components\Modal;
use Illuminate\Support\Facades\Auth;

class DebtMutationController extends Controller
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

        if ($request->vendor_id)
            $query->where('vendor_id', $request->vendor_id);

        if ($request->is_open) {
            $isOpen = $request->is_open;

            if ($isOpen == 'open')
                $query->where('is_open', 1);
            else
                $query->where('is_open', 0);
        }

        if ($request->type)
            $query->where('type', $request->type);

        if ($request->transaction_type)
            $query->where('transaction_type', $request->transaction_type);

        if ($request->date_start)
            $query->whereDate('created', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('created', '<=', new \DateTime($request->date_finish));

        $query->orderBy('created', 'desc');

        if (!in_array(Auth::user()->role, $fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        $datas = $query->paginate(40)->withQueryString();

        $options = self::staticOptions();

        return view('pages.DebtMutationIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $fullAccess = ['owner', 'admin'];

        $request->validate([
            'id' => ['nullable', 'exists:debt_mutations,id'],
            'branch_id' => ['required_without:id', 'exists:branches,id'],
            'project_id' => ['required_without:id', 'exists:projects,id'],
            'vendor_id' => ['required_without:id', 'exists:vendors,id'],

            'type' => ['required_without:id', 'numeric'],
            'transaction_type' => ['required_without:id', 'numeric'],
            'amount' => ['required', 'numeric'],
            'notes' => ['required', 'string', 'max:255'],
            'created' => ['required', 'date'],
        ]);

        $row = Model::findOrNew($request->id);

        $oldAmount = $row->amount;

        if ($row->id && !$row->is_open)
            return redirect()->back()->withErrors(['messages' => 'Sudah ditutup.']);

        if (!$row->id) {
            if (in_array(Auth::user()->role, $fullAccess))
                $row->branch_id = $request->branch_id;
            else
                $row->branch_id = Auth::user()->branch_id;

            $row->project_id = $request->project_id;
            $row->vendor_id = $request->vendor_id;

            $row->type = $request->type;
            $row->transaction_type = $request->transaction_type;
        }
        $row->created = $request->created;
        $row->amount = $request->amount;
        $row->notes = $request->notes;

        // $repo = new DebtMutation($row);
        // $repo->setOldAmount($oldAmount);
        // $repo->save();

        $balance = DebtBalance::firstOrNew([
                                'branch_id' => $row->branch_id,
                                'project_id' => $row->project_id,
                                'vendor_id' => $row->vendor_id,
                                'type' => $row->type
                            ]);

        $totalBalance = Model::where('branch_id', $row->branch_id)
                            ->where('project_id', $row->project_id)
                            ->where('vendor_id', $row->vendor_id)
                            ->where('type', $row->type)
                            ->get();

        $totalBalancePlus = $totalBalance->where('transaction_type', Model::TRANSACTION_TYPE_ADD)->sum('amount');
        $totalBalanceMinus = $totalBalance->where('transaction_type', Model::TRANSACTION_TYPE_SUBTRACT)->sum('amount');
        $totalBalance = $totalBalancePlus - $totalBalanceMinus;


        if ($row->transaction_type == Model::TRANSACTION_TYPE_ADD) {
            if (!$row->id)
                $balance->total = $totalBalance + $row->amount;
            else if ($row->id && $oldAmount != $row->amount)
                $balance->total = $totalBalance + $row->amount - $oldAmount;

            $row->save();
            $balance->save();
        } else if ($row->transaction_type == Model::TRANSACTION_TYPE_SUBTRACT) {
            if ($totalBalance < $row->amount)
                return redirect()->back()->withErrors(['messages' => 'Saldo hutang/piutang kurang dari yang tersedia.']);

            if (!$row->id)
                $balance->total = $totalBalance - $row->amount;
            else if ($row->id && $oldAmount != $row->amount)
                $balance->total = $balance->total - $row->amount + $oldAmount;

            if ($balance->total < 0)
                return redirect()->back()->withErrors(['messages' => 'Saldo hutang/piutang kurang dari yang tersedia.']);

            $row->save();
            $balance->save();
        }

        return redirect()->back()->with('f-msg', 'Mutasi hutang/piutang berhasil disimpan.');
    }

    public function show($id)
    {
        $data = Model::findOrFail($id);
        $options = self::staticOptions();

        return view('pages.DebtMutationDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);

        $balance = DebtBalance::firstOrNew([
            'branch_id' => $row->branch_id,
            'project_id' => $row->project_id,
            'vendor_id' => $row->vendor_id,
            'type' => $row->type
        ]);

        if ($row->transaction_type == Model::TRANSACTION_TYPE_ADD) {
            $balance->total -= $row->amount;
        } else if ($row->transaction_type == Model::TRANSACTION_TYPE_SUBTRACT) {
            $balance->total += $row->amount;
        }

        if ($balance->total < 0)
            return redirect()->back()->withErrors(['messages' => 'Saldo hutang/piutang kurang dari yang tersedia.']);

        $balance->save();

        $row->delete();

        return redirect()->back()->with('f-msg', 'Mutasi hutang/piutang berhasil dihapus.');
    }

    public function changeIsOpen($id)
    {
        $row = Model::findOrFail($id);
        $row->is_open = !$row->is_open;

        $row->save();

        return redirect()->back()->with('f-msg', 'Status berhasil diubah.');
    }

    public function balance()
    {
        $fullAccess = ['owner', 'admin'];

        $query = DebtBalance::select('*');

        if (!in_array(Auth::user()->role, $fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        $datas = $query->paginate(40)->withQueryString();

        return view('pages.DebtBalanceIndex', compact('datas'));
    }

    public static function staticOptions()
    {
        $fullAccess = ['owner', 'admin'];

        $branches = Branch::all();

        if (!in_array(Auth::user()->role, $fullAccess))
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
            ['text' => 'Hutang', 'value' => 1],
            ['text' => 'Piutang', 'value' => 2],
        ];

        $transactionTypes = [
            ['text' => 'Penambahan', 'value' => 1],
            ['text' => 'Pegurangan', 'value' => 2],
        ];

        $options = [
            'branches' => $branches,
            'status' => $status,
            'types' => $types,
            'transactionTypes' => $transactionTypes
        ];

        return $options;
    }
}
