<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User as Model;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $datas = Model::all();

        $branches = Branch::all();
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
        ];

        $options = [
            'branches' => $branches,
            'roles' => $roles,
        ];

        return view('Pages.UserIndex', compact('datas', 'options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'exists:users,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'role' => ['required', 'string', 'max:255', 'in:owner,admin,branch_head,accountant,chasier,material'],
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

        $roles = [
            ['text' => 'Owner', 'value' => 'owner' ],
            ['text' => 'Admin', 'value' => 'admin'],
            ['text' => 'Kepala Cabang', 'value' => 'branch_head'],
            ['text' => 'Akutansi', 'value' => 'accountant'],
            ['text' => 'Kasir', 'value' => 'cashier'],
            ['text' => 'Material', 'value' => 'material'],
        ];

        $options = [
            'branches' => $branches,
            'roles' => $roles,
        ];

        return view('Pages.UserDetail', compact('data', 'options'));
    }

    public function destroy($id)
    {
        $row = Model::findOrFail($id);
        $row->delete();

        return redirect()->back()->with('f-msg', 'Pengguna berhasil dihapus.');
    }
}
