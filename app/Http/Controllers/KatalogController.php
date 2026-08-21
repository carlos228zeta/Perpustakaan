<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    /**
     * Display the catalog for logged-in users.
     */
    public function index(Request $request)
    {
        $query = DB::table('books')
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->leftJoin('publishers', 'books.publisher_id', '=', 'publishers.id')
            ->select(
                'books.*', 
                'categories.name as category_name', 
                'authors.name as author_name',
                'publishers.name as publisher_name'
            )
            ->whereNull('books.deleted_at');

        // Apply filters
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('books.title', 'like', "%{$search}%")
                  ->orWhere('books.isbn', 'like', "%{$search}%")
                  ->orWhere('authors.name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('categories.slug', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('books.type', $request->type);
        }

        // Apply sorting
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('books.created_at', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('books.title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('books.title', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('books.created_at', 'desc');
                break;
        }

        $books = $query->paginate(12)->withQueryString();

        // Get categories for filter dropdown
        $categories = DB::table('categories')
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();

        return view('katalog.index', compact('books', 'categories'));
    }

    /**
     * Display book details in the catalog.
     */
    public function show($id)
    {
        $book = DB::table('books')
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->leftJoin('publishers', 'books.publisher_id', '=', 'publishers.id')
            ->leftJoin('shelves', 'books.shelf_id', '=', 'shelves.id')
            ->select(
                'books.*', 
                'categories.name as category_name', 
                'authors.name as author_name',
                'publishers.name as publisher_name',
                'shelves.name as shelf_name',
                'shelves.location as shelf_location'
            )
            ->where('books.id', $id)
            ->whereNull('books.deleted_at')
            ->first();

        if (!$book) {
            abort(404, 'Buku tidak ditemukan');
        }

        // Get copies information
        $copies = DB::table('book_copies')
            ->where('book_id', $id)
            ->orderBy('copy_code')
            ->get();
            
        $availableCount = $copies->where('status', 'available')->count();
        $totalCopies = $copies->count();

        // Related books (same category)
        $relatedBooks = DB::table('books')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->select('books.*', 'authors.name as author_name')
            ->where('books.category_id', $book->category_id)
            ->where('books.id', '!=', $id)
            ->whereNull('books.deleted_at')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('katalog.show', compact('book', 'copies', 'availableCount', 'totalCopies', 'relatedBooks'));
    }
}
