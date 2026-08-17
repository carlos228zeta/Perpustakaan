<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $librarianRoleId = DB::table('roles')->where('name', 'librarian')->value('id');

        $query = DB::table('users')
            ->where('role_id', $librarianRoleId);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $librarians = $query->orderBy('name', 'asc')->paginate(10);

        return view('petugas.index', compact('librarians', 'search'));
    }

    public function create()
    {
        return view('petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $librarianRoleId = DB::table('roles')->where('name', 'librarian')->value('id');

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $librarianRoleId,
            'status' => 'active',
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas perpustakaan baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $librarian = User::findOrFail($id);
        return view('petugas.edit', compact('librarian'));
    }

    public function update(Request $request, $id)
    {
        $librarian = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$id}",
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $librarian->name = $request->name;
        $librarian->email = $request->email;
        if ($request->filled('password')) {
            $librarian->password = Hash::make($request->password);
        }
        $librarian->save();

        return redirect()->route('petugas.index')->with('success', 'Data petugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $librarian = User::findOrFail($id);
        $librarian->delete();

        return redirect()->route('petugas.index')->with('success', 'Petugas perpustakaan berhasil dihapus.');
    }
}
