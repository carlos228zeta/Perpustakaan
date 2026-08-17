<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Shelf;
use App\Models\Category;

class MasterDataController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'penulis');
        $search = $request->input('search');

        $authors = collect();
        $publishers = collect();
        $shelves = collect();

        if ($tab == 'penulis') {
            $q = DB::table('authors');
            if ($search) { $q->where('name', 'like', "%{$search}%"); }
            $authors = $q->orderBy('name')->paginate(15);
        } elseif ($tab == 'penerbit') {
            $q = DB::table('publishers');
            if ($search) { $q->where('name', 'like', "%{$search}%"); }
            $publishers = $q->orderBy('name')->paginate(15);
        } elseif ($tab == 'rak') {
            $q = DB::table('shelves');
            if ($search) { 
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%"); 
            }
            $shelves = $q->orderBy('code')->paginate(15);
        }

        return view('admin.masterdata.index', compact('tab', 'search', 'authors', 'publishers', 'shelves'));
    }

    // --- AUTHOR CRUD ---
    public function storeAuthor(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('authors')->insert([
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Penulis berhasil ditambahkan!');
    }

    public function updateAuthor(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('authors')->where('id', $id)->update([
            'name' => trim($request->name),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Penulis berhasil diperbarui!');
    }

    public function destroyAuthor($id)
    {
        DB::table('authors')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Penulis berhasil dihapus!');
    }

    // --- PUBLISHER CRUD ---
    public function storePublisher(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('publishers')->insert([
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Penerbit berhasil ditambahkan!');
    }

    public function updatePublisher(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('publishers')->where('id', $id)->update([
            'name' => trim($request->name),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Penerbit berhasil diperbarui!');
    }

    public function destroyPublisher($id)
    {
        DB::table('publishers')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Penerbit berhasil dihapus!');
    }

    // --- SHELF CRUD ---
    public function storeShelf(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255'
        ]);
        DB::table('shelves')->insert([
            'code' => trim($request->code),
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Lokasi Rak berhasil ditambahkan!');
    }

    public function updateShelf(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255'
        ]);
        DB::table('shelves')->where('id', $id)->update([
            'code' => trim($request->code),
            'name' => trim($request->name),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Lokasi Rak berhasil diperbarui!');
    }

    public function destroyShelf($id)
    {
        DB::table('shelves')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Lokasi Rak berhasil dihapus!');
    }

    public function bulkDestroyAuthor(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('authors')->whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', "{$count} data Penulis terpilih berhasil dihapus!");
        }
        return redirect()->back()->with('error', 'Tidak ada data Penulis yang dipilih.');
    }

    public function bulkDestroyPublisher(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('publishers')->whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', "{$count} data Penerbit terpilih berhasil dihapus!");
        }
        return redirect()->back()->with('error', 'Tidak ada data Penerbit yang dipilih.');
    }

    public function bulkDestroyShelf(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('shelves')->whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', "{$count} data Lokasi Rak terpilih berhasil dihapus!");
        }
        return redirect()->back()->with('error', 'Tidak ada data Lokasi Rak yang dipilih.');
    }
}
