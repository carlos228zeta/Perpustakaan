<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CetakLaporanController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'borrowing');

        if ($type === 'books') {
            $data = DB::table('books')
                ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
                ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
                ->leftJoin('publishers', 'books.publisher_id', '=', 'publishers.id')
                ->leftJoin('shelves', 'books.shelf_id', '=', 'shelves.id')
                ->select(
                    'books.*',
                    'categories.name as category_name',
                    'authors.name as author_name',
                    'publishers.name as publisher_name',
                    'shelves.code as shelf_code'
                )
                ->whereNull('books.deleted_at')
                ->get();
        } elseif ($type === 'fines') {
            $data = DB::table('fines')
                ->join('users', 'fines.user_id', '=', 'users.id')
                ->select('fines.*', 'users.name as user_name')
                ->orderBy('fines.created_at', 'desc')
                ->get();
        } elseif ($type === 'members') {
            $data = DB::table('users')
                ->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->leftJoin('teachers', 'users.id', '=', 'teachers.user_id')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->select(
                    'users.*',
                    'roles.display_name as role_name',
                    'students.nis',
                    'teachers.nip'
                )
                ->whereIn('users.role_id', [3, 4])
                ->get();
        } else {
            $data = DB::table('borrowings')
                ->join('users', 'borrowings.user_id', '=', 'users.id')
                ->join('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
                ->join('book_copies', 'borrowing_items.book_copy_id', '=', 'book_copies.id')
                ->join('books', 'book_copies.book_id', '=', 'books.id')
                ->select(
                    'borrowings.*',
                    'users.name as user_name',
                    'books.title as book_title',
                    'book_copies.copy_code'
                )
                ->orderBy('borrowings.created_at', 'desc')
                ->get();
        }

        return view('admin.laporan.index', compact('data', 'type'));
    }

    public function exportCsv(Request $request)
    {
        $type = $request->input('type', 'borrowing');
        $fileName = "laporan_{$type}_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM

            if ($type === 'books') {
                fputcsv($file, ['ID', 'ISBN', 'Judul Buku', 'Kategori', 'Penulis', 'Penerbit', 'Tahun']);
                $rows = DB::table('books')
                    ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
                    ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
                    ->leftJoin('publishers', 'books.publisher_id', '=', 'publishers.id')
                    ->select('books.id', 'books.isbn', 'books.title', 'categories.name as category_name', 'authors.name as author_name', 'publishers.name as publisher_name', 'books.publication_year')
                    ->whereNull('books.deleted_at')->get();

                foreach ($rows as $r) {
                    fputcsv($file, [$r->id, $r->isbn, $r->title, $r->category_name, $r->author_name, $r->publisher_name, $r->publication_year]);
                }
            } elseif ($type === 'fines') {
                fputcsv($file, ['ID', 'Peminjam', 'Jumlah (Rp)', 'Alasan', 'Status', 'Tanggal Dibuat']);
                $rows = DB::table('fines')
                    ->join('users', 'fines.user_id', '=', 'users.id')
                    ->select('fines.*', 'users.name as user_name')
                    ->get();
                foreach ($rows as $r) {
                    fputcsv($file, [$r->id, $r->user_name, $r->amount, $r->reason, $r->status, $r->created_at]);
                }
            } else {
                fputcsv($file, ['ID', 'Peminjam', 'Buku', 'Kode Eksemplar', 'Tgl Pinjam', 'Jatuh Tempo', 'Status']);
                $rows = DB::table('borrowings')
                    ->join('users', 'borrowings.user_id', '=', 'users.id')
                    ->join('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
                    ->join('book_copies', 'borrowing_items.book_copy_id', '=', 'book_copies.id')
                    ->join('books', 'book_copies.book_id', '=', 'books.id')
                    ->select('borrowings.*', 'users.name as user_name', 'books.title as book_title', 'book_copies.copy_code')
                    ->get();
                foreach ($rows as $r) {
                    fputcsv($file, [$r->id, $r->user_name, $r->book_title, $r->copy_code, $r->borrow_date, $r->due_date, $r->status]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
