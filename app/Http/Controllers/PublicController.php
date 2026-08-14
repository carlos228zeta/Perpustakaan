<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    /**
     * Display the public homepage.
     */
    public function index(Request $request)
    {
        $search = $request->input('q');

        // Latest Books (6 items)
        $latestBooks = DB::table('books')
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->select('books.*', 'categories.name as category_name', 'authors.name as author_name')
            ->whereNull('books.deleted_at')
            ->orderBy('books.created_at', 'desc')
            ->limit(6)
            ->get();

        // Popular Books (6 items)
        $popularBooks = DB::table('books')
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->select('books.*', 'categories.name as category_name', 'authors.name as author_name')
            ->whereNull('books.deleted_at')
            ->orderBy('books.id', 'asc')
            ->limit(6)
            ->get();

        // Categories with book count
        $categories = DB::table('categories')
            ->leftJoin('books', 'categories.id', '=', 'books.category_id')
            ->select('categories.id', 'categories.name', 'categories.slug', DB::raw('count(books.id) as total_books'))
            ->groupBy('categories.id', 'categories.name', 'categories.slug')
            ->get();

        return view('public.home', compact('latestBooks', 'popularBooks', 'categories', 'search'));
    }

    /**
     * Display the full book catalog with search, filter, and pagination.
     */
    public function catalog(Request $request)
    {
        $search = $request->input('q');
        $categoryId = $request->input('category_id');
        $authorId = $request->input('author_id');
        $sortBy = $request->input('sort', 'latest');

        $query = DB::table('books')
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->select(
                'books.*',
                'categories.name as category_name',
                'authors.name as author_name'
            )
            ->whereNull('books.deleted_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('books.title', 'like', "%{$search}%")
                  ->orWhere('books.isbn', 'like', "%{$search}%")
                  ->orWhere('authors.name', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('books.category_id', $categoryId);
        }

        if ($authorId) {
            $query->where('books.author_id', $authorId);
        }

        // Sorting
        if ($sortBy === 'title_asc') {
            $query->orderBy('books.title', 'asc');
        } elseif ($sortBy === 'title_desc') {
            $query->orderBy('books.title', 'desc');
        } else {
            $query->orderBy('books.created_at', 'desc');
        }

        $books = $query->paginate(12)->appends($request->all());

        // Attach copies availability calculation
        foreach ($books as $book) {
            $totalCopies = DB::table('book_copies')->where('book_id', $book->id)->count();
            $availableCopies = DB::table('book_copies')->where('book_id', $book->id)->where('status', 'available')->count();
            $book->total_copies = $totalCopies;
            $book->available_copies = $availableCopies;
        }

        $categories = DB::table('categories')->get();
        $authors = DB::table('authors')->get();

        return view('public.catalog', compact('books', 'categories', 'authors', 'search', 'categoryId', 'authorId', 'sortBy'));
    }

    /**
     * Display a specific book detail page.
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
                'shelves.code as shelf_code'
            )
            ->where('books.id', $id)
            ->whereNull('books.deleted_at')
            ->first();

        if (!$book) {
            abort(404, 'Buku tidak ditemukan.');
        }

        $totalCopies = DB::table('book_copies')->where('book_id', $id)->count();
        $availableCopies = DB::table('book_copies')->where('book_id', $id)->where('status', 'available')->count();
        $copies = DB::table('book_copies')->where('book_id', $id)->get();

        return view('public.show', compact('book', 'totalCopies', 'availableCopies', 'copies'));
    }
}
