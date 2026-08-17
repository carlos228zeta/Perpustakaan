<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\LibrarySetting;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterStatus = $request->input('status', 'all'); // all, unpaid, paid

        $finePerDay = (int) LibrarySetting::get('fine_per_day', 1000);
        $now = Carbon::now();

        // 1. Fetch existing fines from 'fines' table
        $finesRecords = DB::table('fines')
            ->join('users', 'fines.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->leftJoin('borrowings', 'fines.borrowing_id', '=', 'borrowings.id')
            ->leftJoin('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
            ->leftJoin('book_copies', 'borrowing_items.book_copy_id', '=', 'book_copies.id')
            ->leftJoin('books', 'book_copies.book_id', '=', 'books.id')
            ->select(
                'fines.id as fine_id',
                'fines.borrowing_id',
                'fines.amount',
                'fines.reason',
                'fines.status as fine_status',
                'fines.created_at as fine_date',
                'users.name as user_name',
                'classes.name as class_name',
                'students.major as student_major',
                'borrowings.due_date',
                'books.title as book_title',
                'book_copies.copy_code'
            )
            ->get();

        // 2. Fetch active overdue borrowings (not yet recorded in fines table)
        $existingBorrowingIds = $finesRecords->pluck('borrowing_id')->filter()->toArray();

        $activeOverdue = DB::table('borrowings')
            ->join('users', 'borrowings.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->join('borrowing_items', 'borrowings.id', '=', 'borrowing_items.borrowing_id')
            ->join('book_copies', 'borrowing_items.book_copy_id', '=', 'book_copies.id')
            ->join('books', 'book_copies.book_id', '=', 'books.id')
            ->whereIn('borrowings.status', ['borrowed', 'approved'])
            ->where('borrowings.due_date', '<', $now)
            ->whereNotIn('borrowings.id', $existingBorrowingIds)
            ->select(
                'borrowings.id as borrowing_id',
                'borrowings.due_date',
                'users.id as user_id',
                'users.name as user_name',
                'classes.name as class_name',
                'students.major as student_major',
                'books.title as book_title',
                'book_copies.copy_code'
            )
            ->get();

        $finesList = [];
        $totalAccumulated = 0;
        $totalUnpaid = 0;
        $totalPaid = 0;
        $overdueMembers = [];

        // Process recorded fines
        foreach ($finesRecords as $f) {
            $amount = (int) $f->amount;
            $isPaid = ($f->fine_status === 'paid');

            $totalAccumulated += $amount;
            if ($isPaid) {
                $totalPaid += $amount;
            } else {
                $totalUnpaid += $amount;
                $overdueMembers[$f->user_name] = true;
            }

            $dueDate = $f->due_date ? Carbon::parse($f->due_date)->format('d/m/Y') : '-';
            $daysLate = 0;
            if ($f->due_date && $f->fine_date) {
                $daysLate = Carbon::parse($f->fine_date)->diffInDays(Carbon::parse($f->due_date));
            }

            $itemData = (object) [
                'id' => $f->fine_id,
                'type' => 'fine_record',
                'borrowing_id' => $f->borrowing_id,
                'user_name' => $f->user_name,
                'book_title' => $f->book_title ?? 'Buku Perpustakaan',
                'copy_code' => $f->copy_code ?? '-',
                'due_date' => $dueDate,
                'days_late' => $daysLate,
                'fine_amount' => $amount,
                'is_paid' => $isPaid,
            ];

            if ($search) {
                if (stripos($itemData->user_name, $search) === false && stripos($itemData->book_title, $search) === false && stripos($itemData->copy_code, $search) === false) {
                    continue;
                }
            }

            if ($filterStatus === 'unpaid' && $itemData->is_paid) continue;
            if ($filterStatus === 'paid' && !$itemData->is_paid) continue;

            $finesList[] = $itemData;
        }

        // Process active overdue borrowings
        foreach ($activeOverdue as $ao) {
            $dueDate = Carbon::parse($ao->due_date);
            $daysLate = $now->diffInDays($dueDate);
            $calculatedFine = $daysLate * $finePerDay;

            if ($calculatedFine <= 0) continue;

            $totalAccumulated += $calculatedFine;
            $totalUnpaid += $calculatedFine;
            $overdueMembers[$ao->user_name] = true;

            $itemData = (object) [
                'id' => $ao->borrowing_id,
                'type' => 'active_overdue',
                'borrowing_id' => $ao->borrowing_id,
                'user_name' => $ao->user_name,
                'book_title' => $ao->book_title,
                'copy_code' => $ao->copy_code,
                'due_date' => $dueDate->format('d/m/Y'),
                'days_late' => $daysLate,
                'fine_amount' => $calculatedFine,
                'is_paid' => false,
            ];

            if ($search) {
                if (stripos($itemData->user_name, $search) === false && stripos($itemData->book_title, $search) === false && stripos($itemData->copy_code, $search) === false) {
                    continue;
                }
            }

            if ($filterStatus === 'paid') continue;

            $finesList[] = $itemData;
        }

        $overdueMembersCount = count($overdueMembers);

        return view('admin.denda.index', compact(
            'finesList',
            'totalAccumulated',
            'totalUnpaid',
            'totalPaid',
            'overdueMembersCount',
            'search',
            'filterStatus'
        ));
    }

    public function markAsPaid(Request $request, $id)
    {
        $type = $request->input('type', 'fine_record');

        if ($type === 'fine_record') {
            DB::table('fines')->where('id', $id)->update([
                'status' => 'paid',
                'updated_at' => now(),
            ]);

            // If there is an associated borrowing, mark it returned as well
            $fine = DB::table('fines')->where('id', $id)->first();
            if ($fine && $fine->borrowing_id) {
                DB::table('borrowings')->where('id', $fine->borrowing_id)->update([
                    'status' => 'returned',
                    'return_date' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // Active overdue borrowing
            $borrowing = DB::table('borrowings')->where('id', $id)->first();
            if ($borrowing) {
                $finePerDay = (int) LibrarySetting::get('fine_per_day', 1000);
                $dueDate = Carbon::parse($borrowing->due_date);
                $now = Carbon::now();
                $daysLate = $now->greaterThan($dueDate) ? $now->diffInDays($dueDate) : 0;
                $fineAmount = $daysLate * $finePerDay;

                // Create paid fine record
                DB::table('fines')->insert([
                    'borrowing_id' => $id,
                    'user_id' => $borrowing->user_id,
                    'amount' => $fineAmount,
                    'reason' => "Keterlambatan pengembalian {$daysLate} hari",
                    'status' => 'paid',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Update borrowing status
                DB::table('borrowings')->where('id', $id)->update([
                    'status' => 'returned',
                    'return_date' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Denda berhasil ditandai LUNAS dan buku telah berhasil dikembalikan!');
    }
}
