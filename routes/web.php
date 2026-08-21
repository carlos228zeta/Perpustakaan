<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\RiwayatPinjamController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengaturanController;

/*
|--------------------------------------------------------------------------
| Web Routes - Library Management System Tzu Chi Cengkareng
|--------------------------------------------------------------------------
*/

Route::get('/seed-chart', function() {
    $student = \Illuminate\Support\Facades\DB::table('users')->where('role_id', 3)->first();
    if (!$student) return 'Gagal: Tidak ada data siswa.';
    
    // 1. Bersihkan data sebelumnya (Reset)
    \Illuminate\Support\Facades\DB::table('fines')->delete();
    \Illuminate\Support\Facades\DB::table('borrowing_items')->delete();
    \Illuminate\Support\Facades\DB::table('borrowings')->delete();
    \Illuminate\Support\Facades\DB::table('book_copies')->update(['status' => 'available']);
    
    $copies = \Illuminate\Support\Facades\DB::table('book_copies')->where('status', 'available')->get();
    if ($copies->isEmpty()) return 'Gagal: Tidak ada eksemplar buku yang tersedia.';
    
    $copyIndex = 0;
    $now = \Carbon\Carbon::now();

    // 2. Buat Data Denda (Telat Balikin) & Overdue Aktif
    // Overdue Aktif (Belum kembali, telat 5 hari)
    for ($i = 0; $i < 3; $i++) {
        $borrowId = \Illuminate\Support\Facades\DB::table('borrowings')->insertGetId([
            'user_id' => $student->id,
            'approved_by' => 1,
            'borrow_date' => $now->copy()->subDays(12)->format('Y-m-d'),
            'due_date' => $now->copy()->subDays(5)->format('Y-m-d'),
            'status' => 'borrowed',
            'created_at' => $now->copy()->subDays(12)->format('Y-m-d H:i:s'),
            'updated_at' => $now->copy()->subDays(12)->format('Y-m-d H:i:s'),
        ]);
        
        \Illuminate\Support\Facades\DB::table('borrowing_items')->insert([
            'borrowing_id' => $borrowId,
            'book_copy_id' => $copies[$copyIndex]->id,
            'created_at' => $now->copy()->subDays(12)->format('Y-m-d H:i:s'),
            'updated_at' => $now->copy()->subDays(12)->format('Y-m-d H:i:s'),
        ]);
        \Illuminate\Support\Facades\DB::table('book_copies')->where('id', $copies[$copyIndex]->id)->update(['status' => 'borrowed']);
        $copyIndex++;
    }

    // Denda (Sudah kembali, tapi kena denda)
    for ($i = 0; $i < 2; $i++) {
        $borrowId = \Illuminate\Support\Facades\DB::table('borrowings')->insertGetId([
            'user_id' => $student->id,
            'approved_by' => 1,
            'borrow_date' => $now->copy()->subDays(20)->format('Y-m-d'),
            'due_date' => $now->copy()->subDays(13)->format('Y-m-d'),
            'return_date' => $now->copy()->subDays(10)->format('Y-m-d'),
            'status' => 'returned',
            'created_at' => $now->copy()->subDays(20)->format('Y-m-d H:i:s'),
            'updated_at' => $now->copy()->subDays(10)->format('Y-m-d H:i:s'),
        ]);
        
        \Illuminate\Support\Facades\DB::table('borrowing_items')->insert([
            'borrowing_id' => $borrowId,
            'book_copy_id' => $copies[$copyIndex]->id,
            'created_at' => $now->copy()->subDays(20)->format('Y-m-d H:i:s'),
            'updated_at' => $now->copy()->subDays(10)->format('Y-m-d H:i:s'),
        ]);
        
        \Illuminate\Support\Facades\DB::table('fines')->insert([
            'user_id' => $student->id,
            'borrowing_id' => $borrowId,
            'amount' => 3000,
            'reason' => 'Keterlambatan 3 hari',
            'status' => 'unpaid',
            'created_at' => $now->copy()->subDays(10)->format('Y-m-d H:i:s'),
            'updated_at' => $now->copy()->subDays(10)->format('Y-m-d H:i:s'),
        ]);
        $copyIndex++;
    }

    // 3. Pola S-Curve untuk 7 hari terakhir
    $pattern = [2, 6, 15, 24, 18, 9, 3]; 
    
    foreach ($pattern as $dayOffset => $count) {
        $date = clone $now;
        $date->subDays(6 - $dayOffset);
        $dateStr = $date->format('Y-m-d');
        
        for ($j = 0; $j < $count; $j++) {
            if (!isset($copies[$copyIndex])) $copyIndex = 0;
            
            $borrowId = \Illuminate\Support\Facades\DB::table('borrowings')->insertGetId([
                'user_id' => $student->id,
                'approved_by' => 1,
                'borrow_date' => $dateStr,
                'due_date' => (clone $date)->addDays(7)->format('Y-m-d'),
                'status' => 'borrowed',
                'created_at' => $dateStr . ' 10:00:00',
                'updated_at' => $dateStr . ' 10:00:00',
            ]);
            
            \Illuminate\Support\Facades\DB::table('borrowing_items')->insert([
                'borrowing_id' => $borrowId,
                'book_copy_id' => $copies[$copyIndex]->id,
                'created_at' => $dateStr . ' 10:00:00',
                'updated_at' => $dateStr . ' 10:00:00',
            ]);
            
            \Illuminate\Support\Facades\DB::table('book_copies')
                ->where('id', $copies[$copyIndex]->id)
                ->update(['status' => 'borrowed']);
                
            $copyIndex++;
        }
    }
    
    return redirect()->route('admin.dashboard')->with('success', 'Data direset! Data denda & Kurva S berhasil dibuat.');
});
// Public Routes (Accessible without login)
Route::get('/', [PublicController::class, 'index'])->name('home');

Auth::routes();

// Protected Routes (Login required)
Route::middleware(['auth'])->group(function () {
    
    // Catalog (Backend)
    Route::get('/katalog', [\App\Http\Controllers\KatalogController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/{id}', [\App\Http\Controllers\KatalogController::class, 'show'])->name('katalog.show');
    
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
        Route::delete('/activities/clear', [DashboardController::class, 'clearActivities'])->name('admin.activities.clear');
        Route::get('/banner', [\App\Http\Controllers\BannerController::class, 'index'])->name('banner.index');
        Route::post('/banner', [\App\Http\Controllers\BannerController::class, 'update'])->name('banner.update');
        Route::post('/banner/delete-slide/{index}', [\App\Http\Controllers\BannerController::class, 'deleteSlide'])->name('banner.deleteSlide');
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
        Route::resource('petugas', PetugasController::class);
        
        Route::delete('/buku/delete-all', [BukuController::class, 'deleteAll'])->name('buku.deleteAll');
        Route::delete('/buku/bulk-delete', [BukuController::class, 'bulkDelete'])->name('buku.bulkDelete');
    });

    // Admin & Librarian Operations
    Route::middleware(['role:admin,librarian'])->prefix('admin')->group(function () {
        Route::resource('kategori', KategoriController::class);
        Route::resource('buku', BukuController::class);
        Route::resource('guru', GuruController::class);
        Route::get('/siswa/import-template', [SiswaController::class, 'importTemplate'])->name('siswa.importTemplate');
        Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::resource('siswa', SiswaController::class);
        Route::resource('peminjaman', RiwayatPinjamController::class);
        
        Route::get('/denda/laporan', [\App\Http\Controllers\DendaController::class, 'laporanPembayaran'])->name('denda.laporan');
        Route::get('/denda', [\App\Http\Controllers\DendaController::class, 'index'])->name('denda.index');
        Route::post('/denda/{id}/pay', [\App\Http\Controllers\DendaController::class, 'markAsPaid'])->name('denda.pay');
        
        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::post('/pengembalian/{id}', [PengembalianController::class, 'returnBook'])->name('pengembalian.process');

        // Master Data Management (Penulis, Penerbit, Rak)
        Route::get('/masterdata', [\App\Http\Controllers\MasterDataController::class, 'index'])->name('masterdata.index');

        // Bulk Destroy Routes (Placed before {id} parameter routes to prevent route collision)
        Route::delete('/masterdata/author/bulk-destroy', [\App\Http\Controllers\MasterDataController::class, 'bulkDestroyAuthor'])->name('masterdata.author.bulkDestroy');
        Route::delete('/masterdata/publisher/bulk-destroy', [\App\Http\Controllers\MasterDataController::class, 'bulkDestroyPublisher'])->name('masterdata.publisher.bulkDestroy');
        Route::delete('/masterdata/shelf/bulk-destroy', [\App\Http\Controllers\MasterDataController::class, 'bulkDestroyShelf'])->name('masterdata.shelf.bulkDestroy');
        Route::delete('/masterdata/class/bulk-destroy', [\App\Http\Controllers\MasterDataController::class, 'bulkDestroyClass'])->name('masterdata.class.bulkDestroy');
        Route::delete('/masterdata/major/bulk-destroy', [\App\Http\Controllers\MasterDataController::class, 'bulkDestroyMajor'])->name('masterdata.major.bulkDestroy');

        // Single Item CRUD Routes
        Route::get('/masterdata/author/create', [\App\Http\Controllers\MasterDataController::class, 'createAuthor'])->name('masterdata.author.create');
        Route::post('/masterdata/author', [\App\Http\Controllers\MasterDataController::class, 'storeAuthor'])->name('masterdata.author.store');
        Route::put('/masterdata/author/{id}', [\App\Http\Controllers\MasterDataController::class, 'updateAuthor'])->name('masterdata.author.update');
        Route::delete('/masterdata/author/{id}', [\App\Http\Controllers\MasterDataController::class, 'destroyAuthor'])->name('masterdata.author.destroy');

        Route::get('/masterdata/publisher/create', [\App\Http\Controllers\MasterDataController::class, 'createPublisher'])->name('masterdata.publisher.create');
        Route::post('/masterdata/publisher', [\App\Http\Controllers\MasterDataController::class, 'storePublisher'])->name('masterdata.publisher.store');
        Route::put('/masterdata/publisher/{id}', [\App\Http\Controllers\MasterDataController::class, 'updatePublisher'])->name('masterdata.publisher.update');
        Route::delete('/masterdata/publisher/{id}', [\App\Http\Controllers\MasterDataController::class, 'destroyPublisher'])->name('masterdata.publisher.destroy');

        Route::get('/masterdata/shelf/create', [\App\Http\Controllers\MasterDataController::class, 'createShelf'])->name('masterdata.shelf.create');
        Route::post('/masterdata/shelf', [\App\Http\Controllers\MasterDataController::class, 'storeShelf'])->name('masterdata.shelf.store');
        Route::put('/masterdata/shelf/{id}', [\App\Http\Controllers\MasterDataController::class, 'updateShelf'])->name('masterdata.shelf.update');
        Route::delete('/masterdata/shelf/{id}', [\App\Http\Controllers\MasterDataController::class, 'destroyShelf'])->name('masterdata.shelf.destroy');

        Route::get('/masterdata/class/create', [\App\Http\Controllers\MasterDataController::class, 'createClass'])->name('masterdata.class.create');
        Route::post('/masterdata/class', [\App\Http\Controllers\MasterDataController::class, 'storeClass'])->name('masterdata.class.store');
        Route::put('/masterdata/class/{id}', [\App\Http\Controllers\MasterDataController::class, 'updateClass'])->name('masterdata.class.update');
        Route::delete('/masterdata/class/{id}', [\App\Http\Controllers\MasterDataController::class, 'destroyClass'])->name('masterdata.class.destroy');

        Route::get('/masterdata/major/create', [\App\Http\Controllers\MasterDataController::class, 'createMajor'])->name('masterdata.major.create');
        Route::post('/masterdata/major', [\App\Http\Controllers\MasterDataController::class, 'storeMajor'])->name('masterdata.major.store');
        Route::put('/masterdata/major/{id}', [\App\Http\Controllers\MasterDataController::class, 'updateMajor'])->name('masterdata.major.update');
        Route::delete('/masterdata/major/{id}', [\App\Http\Controllers\MasterDataController::class, 'destroyMajor'])->name('masterdata.major.destroy');

        // AJAX Quick Store Endpoints for Book Forms
        Route::post('/quick-category', [BukuController::class, 'ajaxStoreCategory'])->name('categories.quickStore');
        Route::post('/quick-author', [BukuController::class, 'ajaxStoreAuthor'])->name('authors.quickStore');
        Route::post('/quick-publisher', [BukuController::class, 'ajaxStorePublisher'])->name('publishers.quickStore');
        Route::post('/quick-shelf', [BukuController::class, 'ajaxStoreShelf'])->name('shelves.quickStore');
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
