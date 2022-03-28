<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\SubBudgetItem as Model;
use App\Models\Category;

class SubBudgetItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Model::select('*');

        if ($request->report_category_id)
            $query->where('report_category_id', $request->report_category_id);

        if ($request->budget_item_group_id)
            $query->where('budget_item_group_id', $request->budget_item_group_id);

        if ($request->budget_item_id)
            $query->where('budget_item_id', $request->budget_item_id);

        if ($request->ajax()) {
            $datas = $query->get();

            return response()->json([
                'datas' => $datas
            ]);
        }

        $datas = $query->paginate(40)->withQueryString();
        $options = self::staticOptions();

        return view('pages.SubBudgetItemIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:budget_items,id'],
            'report_category_id' => [
                'required_without:id',
                Rule::exists('categories', 'id')->where(function ($query) {
                   return $query->where('group_by', 'report_categories');
                }),
            ],
            'budget_item_group_id' => [
                'required_without:id',
                Rule::exists('budget_item_groups', 'id'),
            ],
            'budget_item_id' => [
                'required_without:id',
                Rule::exists('budget_items', 'id'),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:budget_items,name,' . $request->id
            ]
        ]);

        $row = Model::findOrNew($request->id);
        if (!$request->id) {
            $row->report_category_id = $request->report_category_id;
            $row->budget_item_group_id = $request->budget_item_group_id;
            $row->budget_item_id = $request->budget_item_id;
        }
        $row->name = $request->name;

        $row->save();

        return redirect()->back()->with('f-msg', 'Kelompok mata anggaran berhasil disimpan.');
    }

    public function show(Request $request, $id)
    {
       $data = Model::findOrFail($id);

       if (!$data)
            abort(404);

        if ($request->ajax())
            return response()->json($data);
    }

    public function destroy($id)
    {
        $data = Model::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('f-msg', 'Kelompok mata anggaran berhasil dihapus.');
    }

    public static function staticOptions()
    {
        $reportCategories = Category::where('group_by', 'report_categories')
                                    ->get()->map(function ($item) {
                                        return [
                                            'value' => $item->id,
                                            'text' => $item->label
                                        ];
                                    });

        $options = [
            'reportCategories' => $reportCategories,
        ];

        return $options;
    }
}
