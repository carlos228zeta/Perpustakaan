<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('teachers')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->select('teachers.*', 'users.name', 'users.email', 'users.status as user_status');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('teachers.nip', 'like', "%{$search}%")
                  ->orWhere('teachers.subject', 'like', "%{$search}%");
            });
        }

        $teachers = $query->orderBy('users.name', 'asc')->paginate(10);
        return view('guru.index', compact('teachers', 'search'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'nip' => 'nullable|string|unique:teachers,nip',
            'subject' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $teacherRoleId = DB::table('roles')->where('name', 'teacher')->value('id');

        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $teacherRoleId,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('teachers')->insert([
            'user_id' => $userId,
            'nip' => $request->nip,
            'subject' => $request->subject,
            'phone' => $request->phone,
            'department' => $request->department ?? 'Kurikulum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $teacher = DB::table('teachers')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->select('teachers.*', 'users.name', 'users.email')
            ->where('teachers.id', $id)
            ->first();

        if (!$teacher) {
            return redirect()->route('guru.index')->with('error', 'Guru tidak ditemukan.');
        }

        return view('guru.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = DB::table('teachers')->where('id', $id)->first();
        if (!$teacher) {
            return redirect()->route('guru.index')->with('error', 'Data guru tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'nip' => 'nullable|string|unique:teachers,nip,' . $id,
            'phone' => 'nullable|string|max:20',
        ]);

        DB::table('users')->where('id', $teacher->user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        if ($request->filled('password')) {
            DB::table('users')->where('id', $teacher->user_id)->update([
                'password' => Hash::make($request->password)
            ]);
        }

        DB::table('teachers')->where('id', $id)->update([
            'nip' => $request->nip,
            'subject' => $request->subject,
            'phone' => $request->phone,
            'department' => $request->department,
            'updated_at' => now(),
        ]);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $teacher = DB::table('teachers')->where('id', $id)->first();
        if ($teacher) {
            DB::table('users')->where('id', $teacher->user_id)->delete();
            DB::table('teachers')->where('id', $id)->delete();
        }
        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus!');
    }
}
