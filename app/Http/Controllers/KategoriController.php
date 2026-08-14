<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')->paginate(10);
        foreach ($categories as $cat) {
            $cat->books_count = DB::table('books')->where('category_id', $cat->id)->whereNull('deleted_at')->count();
        }
        return view('kategori.index', compact('categories'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name',
            'description' => 'nullable',
        ]);

        DB::table('categories')->insert([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        if (!$category) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak ditemukan.');
        }

        return view('kategori.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $id,
            'description' => 'nullable',
        ]);

        DB::table('categories')->where('id', $id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'updated_at' => now(),
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $hasBooks = DB::table('books')->where('category_id', $id)->whereNull('deleted_at')->exists();
        if ($hasBooks) {
            return redirect()->route('kategori.index')->with('error', 'Kategori ini tidak dapat dihapus karena masih digunakan oleh koleksi buku.');
        }

        DB::table('categories')->where('id', $id)->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
