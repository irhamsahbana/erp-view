<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User as Model;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $datas = Model::all();
        $options = self::staticOptions();

        return view('pages.UserIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:users,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'role' => ['required', 'string', 'max:255', 'in:owner,admin,branch_head,accountant,cashier,material,purchaser'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $request->id],
            'password' => ['required_without:id', 'nullable', 'string', 'max:255'],
            'password_confirmation' => ['required_with:password', 'nullable', 'string', 'max:255', 'same:password'],
        ]);

        $row = Model::findOrNew($request->id);
        if ($request->role != 'owner')
            $row->branch_id = $request->branch_id;

        $row->role = $request->role;
        $row->username = $request->username;
        if ($request->password)
            $row->password = Hash::make($request->password);

        $row->save();

        return redirect()->back()->with('f-msg', 'Pengguna berhasil disimpan.');
    }

    public function edit()
    {
        $data = Model::findOrFail(Auth::user()->id);
        return view('pages.UserEdit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string','min:5', 'max:255'],
            'password_confirmation' => ['required_with:password', 'string', 'max:255', 'same:password']
        ]);

        $row = Model::findOrFail(Auth::user()->id);
        $row->password = bcrypt($request->password);
        $row->save();

        return redirect(route('app'))->with('f-msg', 'Berhasil Mengubah Password Pengguna.');
    }

    public function show($id)
    {
        $data = Model::findOrFail($id);

        $branches = Branch::all();
        if ($branches->isNotEmpty()) {
            $branches = $branches->map(function ($branch) {
                return [
                    'text' => $branch->name,
                    'value' => $branch->id,
                ];
            });
        }

        $options = self::staticOptions();

        return view('pages.UserDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Pengguna berhasil dihapus.');
    }

    public static function staticOptions()
    {
        $branches = Branch::all();

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

        $roles = [
            ['text' => 'Owner', 'value' => 'owner' ],
            ['text' => 'Admin', 'value' => 'admin'],
            ['text' => 'Kepala Cabang', 'value' => 'branch_head'],
            ['text' => 'Akutansi', 'value' => 'accountant'],
            ['text' => 'Kasir', 'value' => 'cashier'],
            ['text' => 'Material', 'value' => 'material'],
            ['text' => 'Purchaser', 'value' => 'purchaser']
        ];

        $options = [
            'branches' => $branches,
            'roles' => $roles,
        ];

        return $options;
    }
}
