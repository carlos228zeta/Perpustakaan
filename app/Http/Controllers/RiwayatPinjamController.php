<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatPinjamController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = DB::table('borrowings')
            ->join('users', 'borrowings.user_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->leftJoin('teachers', 'users.id', '=', 'teachers.user_id')
            ->join('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
            ->join('book_copies', 'borrowing_items.book_copy_id', '=', 'book_copies.id')
            ->join('books', 'book_copies.book_id', '=', 'books.id')
            ->select(
                'borrowings.*',
                'users.name as user_name',
                'users.email as user_email',
                'roles.name as role_name',
                'classes.name as class_name',
                'students.major as student_major',
                'teachers.subject as teacher_subject',
                'books.title as book_title',
                'book_copies.copy_code'
            );

        if ($status) {
            $query->where('borrowings.status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('books.title', 'like', "%{$search}%")
                  ->orWhere('book_copies.copy_code', 'like', "%{$search}%");
            });
        }

        $borrowings = $query->orderBy('borrowings.created_at', 'desc')->paginate(10);

        return view('peminjaman.index', compact('borrowings', 'status', 'search'));
    }

    public function create()
    {
        $users = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.display_name as role_name')
            ->whereIn('users.role_id', [3, 4])
            ->orderBy('users.name', 'asc')
            ->get();

        $books = DB::table('books')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->select('books.*', 'authors.name as author_name')
            ->whereNull('books.deleted_at')
            ->orderBy('books.title', 'asc')
            ->get();

        return view('peminjaman.create', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'nullable|exists:users,id',
            'borrow_date' => 'nullable|date',
            'due_date' => 'nullable|date',
        ]);

        $userId = $request->input('user_id', auth()->id());
        $user = DB::table('users')->where('id', $userId)->first();
        
        $roleName = DB::table('roles')->where('id', $user->role_id)->value('name');
        $maxBorrow = ($roleName === 'teacher') ? 5 : 3;
        $borrowDays = ($roleName === 'teacher') ? 14 : 7;

        // Check active borrowings count
        $activeCount = DB::table('borrowings')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->count();

        if ($activeCount >= $maxBorrow) {
            return redirect()->back()->with('error', "Batas maksimal peminjaman ($maxBorrow buku) telah tercapai.");
        }

        $borrowDate = $request->filled('borrow_date') ? Carbon::parse($request->borrow_date) : Carbon::now();
        $maxDueDate = $borrowDate->copy()->addDays($borrowDays);

        if ($request->filled('due_date')) {
            $selectedDueDate = Carbon::parse($request->due_date);
            if ($selectedDueDate->greaterThan($maxDueDate)) {
                return redirect()->back()->with('error', "Rencana pengembalian melebihi batas maksimal ({$borrowDays} hari). Maksimal pengembalian adalah tanggal " . $maxDueDate->format('d/m/Y') . ".");
            }
            if ($selectedDueDate->lessThanOrEqualTo($borrowDate)) {
                return redirect()->back()->with('error', "Rencana tanggal pengembalian harus setelah tanggal peminjaman.");
            }
            $dueDate = $selectedDueDate;
        } else {
            $dueDate = $maxDueDate;
        }

        // Find available copy
        $copy = DB::table('book_copies')
            ->where('book_id', $request->book_id)
            ->where('status', 'available')
            ->first();

        if (!$copy) {
            // Auto create copy if none exist
            $copyId = DB::table('book_copies')->insertGetId([
                'book_id' => $request->book_id,
                'copy_code' => 'BK-' . str_pad($request->book_id, 4, '0', STR_PAD_LEFT) . '-' . time(),
                'status' => 'borrowed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $copyId = $copy->id;
            DB::table('book_copies')->where('id', $copyId)->update(['status' => 'borrowed']);
        }

        $status = auth()->user()->hasAnyRole(['admin', 'librarian']) ? 'borrowed' : 'pending';

        $borrowingId = DB::table('borrowings')->insertGetId([
            'user_id' => $userId,
            'approved_by' => auth()->id(),
            'borrow_date' => $borrowDate->format('Y-m-d'),
            'due_date' => $dueDate->format('Y-m-d'),
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('borrowing_items')->insert([
            'borrowing_id' => $borrowingId,
            'book_copy_id' => $copyId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $msg = ($status === 'pending') ? 'Pengajuan peminjaman berhasil dikirim dengan rencana pengembalian tanggal ' . $dueDate->format('d/m/Y') . '.' : 'Peminjaman berhasil diproses!';
        return redirect()->route('peminjaman.index')->with('success', $msg);
    }

    public function update(Request $request, $id)
    {
        $status = $request->input('status');
        if (in_array($status, ['approved', 'rejected', 'borrowed'])) {
            DB::table('borrowings')->where('id', $id)->update([
                'status' => $status,
                'approved_by' => auth()->id(),
                'updated_at' => now()
            ]);
        }
        return redirect()->route('peminjaman.index')->with('success', 'Status peminjaman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Hanya Admin yang memiliki hak akses untuk menghapus riwayat peminjaman.');
        }

        DB::table('borrowing_items')->where('borrowing_id', $id)->delete();
        DB::table('borrowings')->where('id', $id)->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Riwayat transaksi peminjaman berhasil dihapus.');
    }
}
