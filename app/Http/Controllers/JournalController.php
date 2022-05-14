<?php

namespace App\Http\Controllers;

use Error;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Journals;
use App\Models\BudgetItem;
use App\Models\SubJournal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BudgetItemGroup;
use App\Models\Jurnal as Model;
use App\Models\Project;
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
            $query->whereDate('created', '>=', new \DateTime($request->date_start));

        if ($request->date_finish)
            $query->whereDate('created', '<=', new \DateTime($request->date_finish));

        $query->orderBy('created', 'desc', 'id','desc');

        if($request->keyword) {
            $list = explode(' ', $request->keyword);
            $list = array_map('trim', $list);

            $query->where(function($query) use ($list) {
                foreach($list as $x) {
                    $pattern = '%' . $x . '%';
                    $query->orWhere('notes', 'like', $pattern);

                }
            });
           }

        if (!in_array(Auth::user()->role, self::$fullAccess))
            $query->where('branch_id', Auth::user()->branch_id);

        if ($request->ajax()) {
            $datas = $query->get();

            return response()->json([
                'datas' => $datas,
            ]);
        }

        $kredit = Category::where('slug', 'kredit')->first();

        // foreach ($subJournal as $sub) {
        //     if($sub->normal_balance_id == $kredit->id){
        //         $totalSub -= $sub->amount;
        //     }else{
        //         $totalSub += $sub->amount;
        //     }
        // }

        $datas = $query->paginate(40);
        $options = self::staticOptions();

        return view('pages.JournalIndex', compact('datas', 'options', 'kredit'));
    }

    public function create()
    {
        if(Auth::user()->branch_id == null){
            $branch = Branch::all();
        }else{
            $branch = Branch::where('id', Auth::user()->branch_id)->get();
        }
        $data = [
            "options" =>self::staticOptions(),
        ];
        return view('pages.JournalCreate', $data);
    }

    public function save(Request $request)
    {
        $row = Journals::findOrNew($request->id);

        $journal = $request->validate([
            'branch_id' => 'required',
            'journal_category_id' => 'required',
            'created' => 'required',
            'notes' => 'required',
        ]);

        $prefix = sprintf('%s/', $row->getTable());
        $postfix = sprintf('/%s.%s', date('m'), date('y'));
        $journal['user_id'] = Auth::user()->id;
        $journal['ref_no'] = $this->generateRefNo($row->getTable(), 4, $prefix, $postfix);

        DB::beginTransaction();
        try {
            // Journals::create($journal);
            $row->branch_id = $journal['branch_id'];
            $row->journal_category_id = $journal['journal_category_id'];
            $row->created = $journal['created'];
            $row->notes = $journal['notes'];
            $row->user_id = $journal['user_id'];
            $row->ref_no = $journal['ref_no'];
            $row->save();
            DB::commit();
            return redirect()->route('detail.journal', $row->id)->with('success', 'Data berhasil ditambahkan');
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
            foreach ($subjurnal as $sub) {
                SubJournal::where('id', $sub->id)->delete();
            }
            Journals::where('id', $id)->delete();
            DB::commit();
            return redirect()->route('journal.index')->with('success', 'Data berhasil dihapus');
        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function edit(Journals $journal)
    {
        if(Auth::user()->branch_id == null){
            $branch = Branch::all();
        }else{
            $branch = Branch::where('id', Auth::user()->branch_id)->get();
        }

        $data = [
            "journal" => $journal,
            'options' => $options = self::staticOptions(),
        ];

        return view('pages.JournalEdit', $data);
    }

    public function change(Journals $journal)
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
            'created' => 'required',
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
        if(Auth::user()->branch_id == null){
            $branch = Branch::all();
        }else{
            $branch = Branch::where('id', Auth::user()->branch_id)->get();
        }

        $budgetItemGroups = BudgetItemGroup::all();

        $subJournal = SubJournal::with('project', 'budgetItemGroup', 'budgetItem', 'subBudgetItem', 'category')->where('journal_id', $journal->id)->get();

        $totalSub = 0;

        $kredit = Category::where('slug', 'kredit')->first();

        foreach ($subJournal as $sub) {
            if($sub->normal_balance_id == $kredit->id){
                $totalSub -= $sub->amount;
            }else{
                $totalSub += $sub->amount;
            }
        }

        $data = [
            'budgetItemGroups' => $budgetItemGroups,
            'journal' => $journal,
            'subJournal' => $subJournal,
            'balances' => Category::where('group_by', 'normal_balances')->get(),
            'totalSub' => $totalSub,
            'options' => self::staticOptions(),
        ];

        return view('pages.JournalDetail', $data);
    }

    public function postSubJournal(Request $request)
    {
        $data = [
            'budget_item_group_id' => $request->budget_item_group_id,
            'journal_id' => $request->journal_id,
            'budget_item_id' => $request->budget_item_id,
            'sub_budget_item_id' => $request->sub_budget_item_id,
            'project_id' => $request->project_id,
            'normal_balance_id' => $request->normal_balance_id,
            'user_id' => Auth::user()->id,
            'amount' => $request->amount,
        ];

        DB::beginTransaction();
        try {
            SubJournal::create($data);
            DB::commit();
            return redirect()->route('detail.journal', ['journal' => $data['journal_id']])->with('success', 'Data berhasil tambah');
        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function updateSubJournal(Request $request)
    {
        $subJournal = $request->validate([
            'budget_item_group_id' => 'required',
            'budget_item_id' => 'required',
            'sub_budget_item_id' => 'required',
            'project_id' => 'required',
            'normal_balance_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            SubJournal::where('id', $request->id_sub_journal)->update($subJournal);
            DB::commit();
            return redirect()->route('detail.journal', ['journal' => $request->journal_id_edit])->with('success', 'Data berhasil diubah');
        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function deleteSubJournal(Request $request)
    {
        DB::beginTransaction();
        try {
            SubJournal::where('id', $request->sub_id)->delete();
            DB::commit();
            return redirect()->route('detail.journal', ['journal' => $request->journal_id])->with('success', 'Data berhasil di hapus');

        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }
    public function deleteSubJournalTemp(Request $request)
    {
        dd($request);

        DB::beginTransaction();
        try {
            TemporarySubJurnal::where('id', $request->sub_temp_id)->delete();
            DB::commit();
            return redirect()->route('detail.journal', ['journal' => $request->journal_id])->with('success', 'Data berhasil di hapus');

        } catch (Error $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public static function staticOptions()
    {
        $branches = Branch::all();
        $category = Category::where('group_by', 'journal_categories')->get();
        $budgetItemGroups = BudgetItemGroup::all();

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

        if ($budgetItemGroups->isNotEmpty()) {
            $budgetItemGroups = $budgetItemGroups->map(function ($big) {
                return [
                    'text' => $big->name,
                    'value' => $big->id,
                ];
            });
        }

        $options = [
            'branches' => $branches,
            'categories' => $category,
            'budgetItemGroups' => $budgetItemGroups,
        ];

        return $options;
    }
}
