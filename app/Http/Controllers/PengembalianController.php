<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('borrowings')
            ->join('users', 'borrowings.user_id', '=', 'users.id')
            ->join('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
            ->join('book_copies', 'borrowing_items.book_copy_id', '=', 'book_copies.id')
            ->join('books', 'book_copies.book_id', '=', 'books.id')
            ->select(
                'borrowings.*',
                'users.name as user_name',
                'books.title as book_title',
                'book_copies.copy_code',
                'book_copies.id as copy_id'
            )
            ->whereIn('borrowings.status', ['borrowed', 'approved']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('books.title', 'like', "%{$search}%")
                  ->orWhere('book_copies.copy_code', 'like', "%{$search}%");
            });
        }

        $borrowings = $query->orderBy('borrowings.due_date', 'asc')->paginate(10);

        return view('pengembalian.index', compact('borrowings', 'search'));
    }

    public function returnBook(Request $request, $id)
    {
        $borrowing = DB::table('borrowings')->where('id', $id)->first();
        if (!$borrowing) {
            return redirect('/admin/pengembalian')->with('error', 'Transaksi peminjaman tidak ditemukan.');
        }

        $now = Carbon::now();
        $dueDate = Carbon::parse($borrowing->due_date);
        $overdueDays = $now->greaterThan($dueDate) ? $now->diffInDays($dueDate) : 0;
        $fineAmount = $overdueDays * 1000; // Rp 1.000 / hari

        $item = DB::table('borrowing_items')->where('borrowing_id', $id)->first();

        DB::transaction(function () use ($id, $borrowing, $item, $now, $overdueDays, $fineAmount) {
            $updateData = [
                'status' => 'returned',
                'return_date' => $now->format('Y-m-d'),
                'updated_at' => $now,
            ];

            if (Schema::hasColumn('borrowings', 'returned_to')) {
                $updateData['returned_to'] = auth()->id();
            } elseif (Schema::hasColumn('borrowings', 'returned_by')) {
                $updateData['returned_by'] = auth()->id();
            }

            // Update borrowing record
            DB::table('borrowings')->where('id', $id)->update($updateData);

            // Set book copy status back to available
            if ($item) {
                DB::table('book_copies')->where('id', $item->book_copy_id)->update([
                    'status' => 'available',
                    'updated_at' => $now,
                ]);
            }

            // Create fine record if overdue
            if ($overdueDays > 0) {
                DB::table('fines')->insert([
                    'borrowing_id' => $id,
                    'user_id' => $borrowing->user_id,
                    'amount' => $fineAmount,
                    'reason' => "Keterlambatan pengembalian {$overdueDays} hari",
                    'status' => 'unpaid',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Activity Log
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'activity' => 'Memproses pengembalian buku ' . ($overdueDays > 0 ? " (Denda Rp " . number_format($fineAmount, 0, ',', '.') . ")" : ""),
                'module' => 'Pengembalian',
                'model_id' => $id,
                'ip_address' => request()->ip(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        $msg = 'Buku berhasil dikembalikan!';
        if ($overdueDays > 0) {
            $msg .= ' Terlambat ' . $overdueDays . ' hari. Denda dicatat sebesar Rp ' . number_format($fineAmount, 0, ',', '.');
        }

        return redirect('/admin/pengembalian')->with('success', $msg);
    }
}
