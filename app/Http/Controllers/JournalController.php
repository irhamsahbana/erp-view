<?php

namespace App\Http\Controllers;

use Error;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Journals;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Jurnal as Model;
use App\Models\SubJournal;
use App\Models\TemporarySubJurnal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function __construct()
    {
        
    }
    public function index(Request $request)
    {
        $query = Journals::select('*');

        if ($request->branch_id) {
            if (!in_array(Auth::user()->role, self::$fullAccess))
                $query->where('branch_id', Auth::user()->branch_id);
            else
                $query->where('branch_id', $request->branch_id);
        }
        if ($request->category_id)
            $query->where('journal_category_id', $request->category_id);

        if ($request->date_start)
            $query->whereDate('date', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('date', '<=', new \DateTime($request->date_finish));

        $query->orderBy('date', 'desc');

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

        return view('pages.JournalIndex', compact('datas', 'options'));
    }
    public function create()
    {
        $data = [
            "branches" => Branch::all(),
            "categories" => Category::where('group_by', 'journal_categories')->get(),
        ];
        return view('pages.JournalCreate', $data);
    }
    public function save(Request $request)
    {
        $row = Journals::findOrNew($request->id);

        $journal = $request->validate([
            'branch_id' => 'required',
            'journal_category_id' => 'required',
            'date' => 'required',
            'notes' => 'required',
        ]);
        $prefix = sprintf('%s/', $row->getTable());
        $postfix = sprintf('/%s.%s', date('m'), date('y'));
        $journal['user_id'] = Auth::user()->id;
        $journal['ref_no'] = $this->generateRefNo($row->getTable(), 4, $prefix, $postfix);
        DB::beginTransaction();
        try {
            Journals::create($journal);
            DB::commit();
            return redirect()->route('journal.index')->with('success', 'Data berhasil ditambahkan');
        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function delete($id)
    {
        $subjurnal = SubJournal::where('journal_id', $id)->get();
        DB::beginTransaction();
        try {
            Journals::where('id', $id)->delete();
            foreach ($subjurnal as $sub) {
                SubJournal::where('id', $sub->id)->delete();
            }
            DB::commit();
            return redirect()->route('journal.index')->with('success', 'Data berhasil dihapus');
        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function edit(Journals $journal)
    {
        $data = [
            "journal" => $journal,
            "branches" => Branch::all(),
            "categories" => Category::where('group_by', 'journal_categories')->get(),
        ];
        return view('pages.JournalEdit', $data);
    }
    public function update(Request $request, Journals $journal)
    {
        $jurnal = $request->validate([
            'branch_id' => 'required',
            'journal_category_id' => 'required',
            'date' => 'required',
            'voucher_number' => 'required',
            'notes' => 'required',
            'is_open' => 'required',
        ]);
        DB::beginTransaction();
        try {
            Journals::where('id', $journal->id)->update($jurnal);
            DB::commit();
            return redirect()->route('journal.index')->with('success', 'Data berhasil diubah');
        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function detail(Journals $journal)
    {
        // TemporarySubJurnal::where('user_id', Auth::user()->id)->delete();
        $data = [
            'journal' => $journal,
            'subjournals' => SubJournal::with('budgetItemGroup', 'project', 'budgetItem', 'subBudgetItem')->where('journal_id', $journal->id)->get(),
        ];
        return view('pages.JournalDetail', $data);
    }
    public static function staticOptions()
    {
        $branches = Branch::all();
        $category = Category::where('group_by', 'journal_categories')->get();

        if (!in_array(Auth::user()->role, self::$fullAccess))
            $branches = $branches->where('id', Auth::user()->branch_id);

        if ($branches->isNotEmpty()) {
            $branches = $branches->map(function ($branch) {
                return [
                    'text' => $branch->name,
                    'value' => $branch->id,
                ];
            });
        }

        if ($category->isNotEmpty()) {
            $category = $category->map(function ($category) {
                return [
                    'text' => $category->label,
                    'value' => $category->id,
                ];
            });
        }
        $options = [
            'branches' => $branches,
            'categories' => $category,
        ];

        return $options;
    }
}