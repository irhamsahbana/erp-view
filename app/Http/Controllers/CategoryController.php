<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Models\Category as Model;

class CategoryController extends Controller
{
    //allowed category that can be store by user
    private static $allowed = ['journal_categories', 'debt_types'];

    public function index(Request $request)
    {
        $query = Model::select('*');

        if ($request->category)
            $query->where('group_by', $request->category);
        else
            return redirect()->route('category.list');

        $datas = $query->paginate(40)->withQueryString();

        return view('pages.CategoryIndex', compact('datas'));
    }

    public function list()
    {
        return view('pages.CategoryList');
    }

    public function store(Request $request)
    {
        // change $request->label as slug and assign to $request->slug
        $request->merge(['slug' => Str::slug($request->label)]);

        $request->validate([
            'id' => ['nullable', 'exists:categories,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'group_by' => ['required', Rule::in(self::$allowed)],
            'label' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                Rule::unique('categories')->ignore($request->id)->where(function ($query) use ($request) {
                    return $query->where('group_by', $request->group_by);
                })
            ],
            'disabled' => ['nullable', 'boolean'],
            'notes' => ['nullable'],
        ]);

        $row = Model::findOrNew($request->id);
        $row->category_id = $request->category_id;
        $row->group_by = $request->group_by;
        $row->label = $request->label;
        $row->slug = $request->slug;
        $row->disabled = $request->disabled;
        $row->notes = $request->notes;
        $row->save();

        return redirect()->back()->with('f-msg', 'Kategori berhasil disimpan.');
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
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Kategori berhasil dihapus.');
    }
}
