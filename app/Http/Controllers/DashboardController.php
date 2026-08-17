<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        $totalUsers = DB::table('users')->count();
        $totalStudents = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'student')
            ->count();
        if ($totalStudents === 0) {
            $totalStudents = DB::table('students')->count();
        }

        $totalTeachers = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'teacher')
            ->count();
        if ($totalTeachers === 0) {
            $totalTeachers = DB::table('teachers')->count();
        }
        $totalLibrarians = DB::table('users')->where('role_id', 2)->count();
        $totalBooks = DB::table('books')->whereNull('deleted_at')->count();
        $totalCopies = DB::table('book_copies')->count();
        $availableCopies = DB::table('book_copies')->where('status', 'available')->count();
        $activeBorrowings = DB::table('borrowings')->whereIn('status', ['borrowed', 'approved'])->count();
        $overdueBorrowings = DB::table('borrowings')
            ->whereIn('status', ['borrowed', 'approved'])
            ->where('due_date', '<', Carbon::now())
            ->count();
        $totalFines = DB::table('fines')->sum('amount');
        $recentActivities = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('activity_logs.created_at', 'desc')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalStudents', 'totalTeachers', 'totalLibrarians',
            'totalBooks', 'totalCopies', 'availableCopies', 'activeBorrowings', 'overdueBorrowings',
            'totalFines', 'recentActivities'
        ));
    }

    /**
     * Clear all activity logs (Admin only)
     */
    public function clearActivities()
    {
        DB::table('activity_logs')->truncate();
        return redirect()->back()->with('success', 'Semua riwayat aktivitas sistem telah berhasil dihapus.');
    }

    /**
     * Librarian Dashboard
     */
    public function librarianDashboard()
    {
        $totalBooks = DB::table('books')->whereNull('deleted_at')->count();
        $totalCopies = DB::table('book_copies')->count();
        $totalStudents = DB::table('students')->count();
        $totalTeachers = DB::table('teachers')->count();
        $activeBorrowings = DB::table('borrowings')->whereIn('status', ['borrowed', 'approved'])->count();
        $overdueBorrowings = DB::table('borrowings')
            ->whereIn('status', ['borrowed', 'approved'])
            ->where('due_date', '<', Carbon::now())
            ->count();
        $totalReservations = DB::table('reservations')->where('status', 'waiting')->count();
        $totalFines = DB::table('fines')->where('status', 'unpaid')->sum('amount');

        $recentActivities = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.name as user_name')
            ->orderBy('activity_logs.created_at', 'desc')
            ->limit(6)
            ->get();

        return view('librarian.dashboard', compact(
            'totalBooks', 'totalCopies', 'totalStudents', 'totalTeachers',
            'activeBorrowings', 'overdueBorrowings', 'totalReservations',
            'totalFines', 'recentActivities'
        ));
    }

    /**
     * Teacher Dashboard
     */
    public function teacherDashboard()
    {
        $userId = auth()->id();

        $borrowedBooks = DB::table('borrowings')
            ->where('user_id', $userId)
            ->whereIn('status', ['borrowed', 'approved'])
            ->count();

        $dueSoon = DB::table('borrowings')
            ->where('user_id', $userId)
            ->whereIn('status', ['borrowed', 'approved'])
            ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(3)])
            ->count();

        $reservations = DB::table('reservations')
            ->where('user_id', $userId)
            ->where('status', 'waiting')
            ->count();

        $fines = DB::table('fines')
            ->where('user_id', $userId)
            ->where('status', 'unpaid')
            ->sum('amount');

        $activeBorrowList = DB::table('borrowings')
            ->join('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
            ->join('book_copies', 'borrowing_items.book_copy_id', '=', 'book_copies.id')
            ->join('books', 'book_copies.book_id', '=', 'books.id')
            ->select('borrowings.*', 'books.title as book_title', 'book_copies.copy_code')
            ->where('borrowings.user_id', $userId)
            ->whereIn('borrowings.status', ['borrowed', 'approved'])
            ->get();

        $popularBooks = DB::table('books')
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->select('books.*', 'categories.name as category_name')
            ->orderBy('books.id', 'asc')
            ->limit(4)
            ->get();

        return view('teacher.dashboard', compact(
            'borrowedBooks', 'dueSoon', 'reservations', 'fines', 'activeBorrowList', 'popularBooks'
        ));
    }

    /**
     * Student Dashboard
     */
    public function studentDashboard()
    {
        $userId = auth()->id();

        $borrowedBooks = DB::table('borrowings')
            ->where('user_id', $userId)
            ->whereIn('status', ['borrowed', 'approved'])
            ->count();

        $dueSoon = DB::table('borrowings')
            ->where('user_id', $userId)
            ->whereIn('status', ['borrowed', 'approved'])
            ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(3)])
            ->count();

        $reservations = DB::table('reservations')
            ->where('user_id', $userId)
            ->where('status', 'waiting')
            ->count();

        $fines = DB::table('fines')
            ->where('user_id', $userId)
            ->where('status', 'unpaid')
            ->sum('amount');

        $activeBorrowList = DB::table('borrowings')
            ->join('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
            ->join('book_copies', 'borrowing_items.book_copy_id', '=', 'book_copies.id')
            ->join('books', 'book_copies.book_id', '=', 'books.id')
            ->select('borrowings.*', 'books.title as book_title', 'book_copies.copy_code')
            ->where('borrowings.user_id', $userId)
            ->whereIn('borrowings.status', ['borrowed', 'approved'])
            ->get();

        $popularBooks = DB::table('books')
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->select('books.*', 'categories.name as category_name')
            ->orderBy('books.id', 'asc')
            ->limit(4)
            ->get();

        return view('student.dashboard', compact(
            'borrowedBooks', 'dueSoon', 'reservations', 'fines', 'activeBorrowList', 'popularBooks'
        ));
    }
}
