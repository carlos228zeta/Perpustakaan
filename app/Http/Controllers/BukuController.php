<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Book;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $query = DB::table('books')
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->leftJoin('publishers', 'books.publisher_id', '=', 'publishers.id')
            ->select(
                'books.id', 
                'books.title', 
                'books.isbn', 
                'books.publication_year', 
                'books.cover_image',
                'categories.name as category_name', 
                'authors.name as author_name',
                'publishers.name as publisher_name'
            )
            ->whereNull('books.deleted_at');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('books.title', 'like', '%' . $search . '%')
                  ->orWhere('authors.name', 'like', '%' . $search . '%')
                  ->orWhere('books.isbn', 'like', '%' . $search . '%');
            });
        }

        if ($categoryId) {
            $query->where('books.category_id', $categoryId);
        }

        $books = $query->orderBy('books.created_at', 'desc')->paginate(10);
        $categories = DB::table('categories')->get();

        foreach ($books as $book) {
            $book->total_copies = DB::table('book_copies')->where('book_id', $book->id)->count();
            $book->available_copies = DB::table('book_copies')->where('book_id', $book->id)->where('status', 'available')->count();
        }

        return view('buku.index', compact('books', 'categories', 'search', 'categoryId'));
    }

    public function create()
    {
        $categories = DB::table('categories')->get();
        $authors = DB::table('authors')->get();
        $publishers = DB::table('publishers')->get();
        $shelves = DB::table('shelves')->get();
        
        return view('buku.create', compact('categories', 'authors', 'publishers', 'shelves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'isbn' => 'nullable|unique:books,isbn|max:50',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'publication_year' => 'nullable|integer',
            'synopsis' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'initial_copies' => 'nullable|integer|min:1|max:20'
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/buku'), $fileName);
            $coverImagePath = 'images/buku/' . $fileName;
        }

        $slug = Str::slug($request->title) . '-' . time();
        $initialCopies = $request->input('initial_copies', 1);

        $bookId = DB::table('books')->insertGetId([
            'title' => $request->title,
            'slug' => $slug,
            'isbn' => $request->isbn,
            'category_id' => $request->category_id,
            'author_id' => $request->author_id,
            'publisher_id' => $request->publisher_id,
            'shelf_id' => $request->shelf_id ?? null,
            'publication_year' => $request->publication_year,
            'synopsis' => $request->synopsis,
            'cover_image' => $coverImagePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Automatically generate book copies
        for ($i = 1; $i <= $initialCopies; $i++) {
            DB::table('book_copies')->insert([
                'book_id' => $bookId,
                'copy_code' => 'BK-' . str_pad($bookId, 4, '0', STR_PAD_LEFT) . '-' . $i,
                'procurement_date' => now()->format('Y-m-d'),
                'condition' => 'good',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('buku.index')->with('success', 'Buku dan eksemplar berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $book = DB::table('books')->where('id', $id)->first();
        if (!$book) {
            return redirect()->route('buku.index')->with('error', 'Buku tidak ditemukan.');
        }

        $categories = DB::table('categories')->get();
        $authors = DB::table('authors')->get();
        $publishers = DB::table('publishers')->get();
        $shelves = DB::table('shelves')->get();

        return view('buku.edit', compact('book', 'categories', 'authors', 'publishers', 'shelves'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'isbn' => 'nullable|max:50|unique:books,isbn,' . $id,
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'publication_year' => 'nullable|integer',
            'synopsis' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $updateData = [
            'title' => $request->title,
            'isbn' => $request->isbn,
            'category_id' => $request->category_id,
            'author_id' => $request->author_id,
            'publisher_id' => $request->publisher_id,
            'shelf_id' => $request->shelf_id ?? null,
            'publication_year' => $request->publication_year,
            'synopsis' => $request->synopsis,
            'updated_at' => now(),
        ];

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/buku'), $fileName);
            $updateData['cover_image'] = 'images/buku/' . $fileName;
        }

        DB::table('books')->where('id', $id)->update($updateData);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        DB::table('books')->where('id', $id)->update(['deleted_at' => now()]);
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }

    public function deleteAll(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->route('buku.index')->with('error', 'Hanya Admin yang memiliki akses untuk menghapus semua data buku.');
        }

        $count = DB::table('books')->whereNull('deleted_at')->count();
        if ($count === 0) {
            return redirect()->route('buku.index')->with('error', 'Tidak ada data buku yang dapat dihapus.');
        }

        DB::table('books')->whereNull('deleted_at')->update(['deleted_at' => now()]);

        return redirect()->route('buku.index')->with('success', "Semua data buku ({$count} buku) berhasil dihapus!");
    }

    public function bulkDelete(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->route('buku.index')->with('error', 'Hanya Admin yang memiliki akses untuk menghapus data buku.');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('books')->whereIn('id', $ids)->whereNull('deleted_at')->count();
            DB::table('books')->whereIn('id', $ids)->whereNull('deleted_at')->update(['deleted_at' => now()]);
            return redirect()->route('buku.index')->with('success', "{$count} buku terpilih berhasil dihapus!");
        }

        return redirect()->route('buku.index')->with('error', 'Tidak ada buku yang dipilih untuk dihapus.');
    }

    public function ajaxStoreCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $id = DB::table('categories')->insertGetId([
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['success' => true, 'id' => $id, 'name' => trim($request->name)]);
    }

    public function ajaxStoreAuthor(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $id = DB::table('authors')->insertGetId([
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['success' => true, 'id' => $id, 'name' => trim($request->name)]);
    }

    public function ajaxStorePublisher(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $id = DB::table('publishers')->insertGetId([
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['success' => true, 'id' => $id, 'name' => trim($request->name)]);
    }

    public function ajaxStoreShelf(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255'
        ]);
        $id = DB::table('shelves')->insertGetId([
            'code' => trim($request->code),
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $displayName = trim($request->code) . ' - ' . trim($request->name);
        return response()->json(['success' => true, 'id' => $id, 'name' => $displayName]);
    }
}
