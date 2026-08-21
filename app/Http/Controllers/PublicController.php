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

        // Categories with book count (excluding deleted books)
        $categories = DB::table('categories')
            ->leftJoin('books', function ($join) {
                $join->on('categories.id', '=', 'books.category_id')
                     ->whereNull('books.deleted_at');
            })
            ->select('categories.id', 'categories.name', 'categories.slug', DB::raw('count(books.id) as total_books'))
            ->groupBy('categories.id', 'categories.name', 'categories.slug')
            ->get();

        return view('public.home', compact('latestBooks', 'popularBooks', 'categories', 'search'));
    }
}
