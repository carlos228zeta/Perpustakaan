<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\File\File;

class AnggotaController extends Controller
{
    public function index()
    {
        $users = User::with('role')->whereIn('role_id', [3, 4])->paginate(10);
        return view('anggota.index', compact('users'));
    }

    public function create()
    {
        return redirect()->back()->with('error', 'Fitur tambah anggota sedang dalam perbaikan struktur.');
    }

    public function store(Request $request)
    {
        return redirect()->back()->with('error', 'Fitur sedang dalam perbaikan.');
    }

    public function show($id)
    {
        return redirect()->back()->with('error', 'Fitur sedang dalam perbaikan.');
    }

    public function edit($id)
    {
        return redirect()->back()->with('error', 'Fitur sedang dalam perbaikan.');
    }

    public function update(Request $request, $id)
    {
        return redirect()->back()->with('error', 'Fitur sedang dalam perbaikan.');
    }

    public function destroy($id)
    {
        return redirect()->back()->with('error', 'Fitur sedang dalam perbaikan.');
    }
}
