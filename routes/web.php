<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\RiwayatPinjamController;
use App\Http\Controllers\CetakLaporanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengaturanController;

/*
|--------------------------------------------------------------------------
| Web Routes - Library Management System Tzu Chi Cengkareng
|--------------------------------------------------------------------------
*/

// Public Routes (Accessible without login)
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/books', [PublicController::class, 'catalog'])->name('public.books.index');
Route::get('/books/{id}', [PublicController::class, 'show'])->name('public.books.show');

Auth::routes();

// Protected Routes (Login required)
Route::middleware(['auth'])->group(function () {
    
    // Redirect /home to appropriate role dashboard
    Route::get('/home', function() {
        return redirect()->route('dashboard');
    });

    // Unified /dashboard redirect route
    Route::get('/dashboard', function() {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('librarian')) {
            return redirect()->route('librarian.dashboard');
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        }
        return redirect()->route('student.dashboard');
    })->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });

    // Admin & Librarian Operations
    Route::middleware(['role:admin,librarian'])->prefix('admin')->group(function () {
        Route::resource('kategori', KategoriController::class);
        Route::resource('buku', BukuController::class);
        Route::resource('guru', GuruController::class);
        Route::resource('siswa', SiswaController::class);
        Route::resource('peminjaman', RiwayatPinjamController::class);
        
        Route::get('/cetaklaporan', [CetakLaporanController::class, 'index'])->name('cetaklaporan');
        Route::get('/cetaklaporan/export', [CetakLaporanController::class, 'exportCsv'])->name('cetaklaporan.export');
        
        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::post('/pengembalian/{id}', [PengembalianController::class, 'returnBook'])->name('pengembalian.process');
    });

    // Librarian Dashboard
    Route::middleware(['role:librarian'])->prefix('librarian')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'librarianDashboard'])->name('librarian.dashboard');
    });

    // Teacher Dashboard
    Route::middleware(['role:teacher'])->prefix('teacher')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teacherDashboard'])->name('teacher.dashboard');
    });

    // Student Dashboard
    Route::middleware(['role:student'])->prefix('student')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'studentDashboard'])->name('student.dashboard');
    });

    // Profile Management
    Route::resource('profile', ProfileController::class)->only('index', 'update', 'edit');
});
