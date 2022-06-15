<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use App\Models\Budget as Model;
use App\Models\Branch;
use App\Models\BudgetItemGroup;
use App\Models\Project;

class BudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('has.access:owner', ['only' => ['changeIsOpen']]);
    }

    public function index(Request $request)
    {
        $query = Model::select('*');

        if($request->branch_id){
            if(!in_array(Auth::user()->role, self::$fullAccess)){
                $query->where('branch_id', Auth::user()->branch_id);
            }else{
                $query->where('branch_id', $request->branch_id);
            }
        }

        if($request->project_id){
            $query->where('project_id', $request->project_id);
        }

        if($request->date_start){
            $query->where('created', '>=', $request->date_start);
        }

        if($request->date_finish){
            $query->where('created', '<=', $request->date_finish);
        }

        if($request->ajax()){
            $datas = $query->get();

            return response()->json([
                'datas' => $datas
            ]);
        }

        $datas = $query->paginate(40)->withQueryString();
        $options = self::staticOptions();
        return view('pages.BudgetIndex', compact('datas', 'options'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => ['required_without:id', 'exists:branches,id'],
            'project_id' => ['required_without:id', 'exists:projects,id'],
            'budget_item_group_id' => ['required'],
            'budget_item_id' => ['required'],
            'sub_budget_item_id' => [
                'required',
                Rule::unique('budgets')->where(function ($query) use ($request) {
                    $query->where('created', $request->created)->where('project_id', $request->project_id);
                }),
            ],
            'amount' => ['required', 'numeric'],
            'created' => ['required', 'date_format:Y'],
        ]);

        Model::create([
            'branch_id' => $request->branch_id,
            'project_id' => $request->project_id,
            'budget_item_group_id' => $request->budget_item_group_id,
            'budget_item_id' => $request->budget_item_id,
            'sub_budget_item_id' => $request->sub_budget_item_id,
            'amount' => $request->amount,
            'created' => $request->created
        ]);

        return redirect()->back()->with('f-msg', 'Anggaran berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = Model::findOrFail($id);
        $options = self::staticOptions();

        return view('pages.BudgetDetail', compact('data', 'options'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $row = Model::find($id);

        if($request->sub_budget_item_id == $row->sub_budget_item_id){
            $unique = '';
        }else{
            $unique = Rule::unique('budgets')->where(
                function ($query) use ($request) {
                    $query->where('created', $request->created)->where('project_id', $request->project_id);
                }
            );
        }
        $budget = $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
            'project_id' => ['required', 'exists:projects,id'],
            'budget_item_group_id' => ['required'],
            'budget_item_id' => ['required'],
            'sub_budget_item_id' => ['required', $unique],
            'amount' => ['required', 'numeric'],
            'created' => ['required', 'date_format:Y'],
        ]);

        Model::where('id', $id)->update($budget);

        return redirect()->route('budget.index')->with('f-msg', 'Anggaran berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();
        return redirect()->back()->with('f-msg', 'Anggaran berhasil dihapus.');
    }

    public function changeIsOpen($id)
    {
        $row = Model::findOrFail($id);
        $row->is_open = !$row->is_open;

        $row->save();

        return redirect()->back()->with('f-msg', 'Status berhasil diubah.');
    }

    public static function staticOptions()
    {
        $branches = Branch::all();
        $budget_item_group = BudgetItemGroup::all();


        if (!in_array(Auth::user()->role, self::$fullAccess))
            $branches = $branches->where('id', Auth::user()->branch_id);

        if ($branches->isNotEmpty())
            $branches = $branches->map(function ($branch) {
                return [
                    'text' => $branch->name,
                    'value' => $branch->id,
                ];
            });

        if ($budget_item_group->isNotEmpty())
        {
            $budget_item_group = $budget_item_group->map(function($group){
                return [
                    'text' => $group->name,
                    'value' => $group->id
                ];
            });
        }

        $years = [];
        for ($year= 1998; $year <= date('Y') + 5; $year++) {
            $years[] = $year;
        }

        $years = collect($years);
        $years = $years->map(function($year){
            return [
                'text' => $year,
                'value' => $year
            ];
        });

        $options = [
            'branches' => $branches,
            'groups' => $budget_item_group,
            'years' => $years
        ];

        return $options;
    }
}
