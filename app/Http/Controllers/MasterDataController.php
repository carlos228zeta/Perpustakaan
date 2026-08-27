<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Shelf;
use App\Models\Category;

class MasterDataController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'penulis');
        $search = $request->input('search');

        $authors = collect();
        $publishers = collect();
        $shelves = collect();
        $classes = collect();
        $majors = collect();

        if ($tab == 'penulis') {
            $q = DB::table('authors');
            if ($search) { $q->where('name', 'like', "%{$search}%"); }
            $authors = $q->orderBy('name')->paginate(15);
        } elseif ($tab == 'penerbit') {
            $q = DB::table('publishers');
            if ($search) { $q->where('name', 'like', "%{$search}%"); }
            $publishers = $q->orderBy('name')->paginate(15);
        } elseif ($tab == 'rak') {
            $q = DB::table('shelves');
            if ($search) { 
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%"); 
            }
            $shelves = $q->orderBy('code')->paginate(15);
        } elseif ($tab == 'kelas') {
            // Auto-populate default classes if empty
            if (DB::table('classes')->count() === 0) {
                $academicYear = DB::table('academic_years')->first();
                if (!$academicYear) {
                    $academicYearId = DB::table('academic_years')->insertGetId([
                        'name' => '2026/2027',
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $academicYearId = $academicYear->id;
                }

                $classesList = [
                    // SMP
                    '7A', '7B', '7C', '7D', '7E',
                    '8A', '8B', '8C', '8D', '8E',
                    '9A', '9B', '9C', '9D', '9E',
                    // SMA
                    'X.1', 'X.2', 'X.3',
                    'XI.1', 'XI.2', 'XI.3',
                    'XII.1', 'XII.2', 'XII.3',
                    // SMK
                    'X PPLG 1', 'X PPLG 2', 'X AKL', 'X MPLB',
                    'XI PPLG 1', 'XI PPLG 2', 'XI AKL', 'XI MPLB',
                    'XII PPLG 1', 'XII PPLG 2', 'XII AKL', 'XII OTKP',
                ];

                foreach ($classesList as $cName) {
                    DB::table('classes')->insertOrIgnore([
                        'name' => $cName,
                        'academic_year_id' => $academicYearId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            $q = DB::table('classes');
            if ($search) { $q->where('name', 'like', "%{$search}%"); }
            $classes = $q->orderBy('id', 'asc')->paginate(15);
        } elseif ($tab == 'jurusan') {
            // Auto-populate default majors if empty
            if (DB::table('majors')->count() === 0) {
                $majorsList = [
                    'MIPA (Matematika dan Ilmu Pengetahuan Alam)',
                    'IPS (Ilmu Pengetahuan Sosial)',
                    'Bahasa dan Budaya',
                    'PPLG (Pengembangan Perangkat Lunak dan Gim)',
                    'AKL (Akuntansi dan Keuangan Lembaga)',
                    'MPLB (Manajemen Perkantoran dan Layanan Bisnis)',
                    'OTKP (Otomatisasi & Tata Kelola Perkantoran)',
                    'Umum (SMP)',
                ];

                foreach ($majorsList as $mName) {
                    DB::table('majors')->insertOrIgnore([
                        'name' => $mName,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            $q = DB::table('majors');
            if ($search) { $q->where('name', 'like', "%{$search}%"); }
            $majors = $q->orderBy('id', 'asc')->paginate(15);
        }

        return view('admin.masterdata.index', compact('tab', 'search', 'authors', 'publishers', 'shelves', 'classes', 'majors'));
    }

    // --- AUTHOR CRUD ---
    public function createAuthor()
    {
        return view('admin.masterdata.create_author');
    }

    public function storeAuthor(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('authors')->insert([
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->route('masterdata.index', ['tab' => 'penulis'])->with('success', 'Data Penulis berhasil ditambahkan!');
    }

    public function updateAuthor(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('authors')->where('id', $id)->update([
            'name' => trim($request->name),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Penulis berhasil diperbarui!');
    }

    public function destroyAuthor($id)
    {
        DB::table('authors')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Penulis berhasil dihapus!');
    }

    // --- PUBLISHER CRUD ---
    public function createPublisher()
    {
        return view('admin.masterdata.create_publisher');
    }

    public function storePublisher(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('publishers')->insert([
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->route('masterdata.index', ['tab' => 'penerbit'])->with('success', 'Data Penerbit berhasil ditambahkan!');
    }

    public function updatePublisher(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('publishers')->where('id', $id)->update([
            'name' => trim($request->name),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Penerbit berhasil diperbarui!');
    }

    public function destroyPublisher($id)
    {
        DB::table('publishers')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Penerbit berhasil dihapus!');
    }

    // --- SHELF CRUD ---
    public function createShelf()
    {
        return view('admin.masterdata.create_shelf');
    }

    public function storeShelf(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255'
        ]);
        DB::table('shelves')->insert([
            'code' => trim($request->code),
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->route('masterdata.index', ['tab' => 'rak'])->with('success', 'Data Lokasi Rak berhasil ditambahkan!');
    }

    public function updateShelf(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255'
        ]);
        DB::table('shelves')->where('id', $id)->update([
            'code' => trim($request->code),
            'name' => trim($request->name),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Lokasi Rak berhasil diperbarui!');
    }

    public function destroyShelf($id)
    {
        DB::table('shelves')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Lokasi Rak berhasil dihapus!');
    }

    // --- CLASS CRUD ---
    public function createClass()
    {
        return view('admin.masterdata.create_class');
    }

    public function storeClass(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('classes')->insert([
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->route('masterdata.index', ['tab' => 'kelas'])->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    public function updateClass(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('classes')->where('id', $id)->update([
            'name' => trim($request->name),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Kelas berhasil diperbarui!');
    }

    public function destroyClass($id)
    {
        DB::table('classes')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Kelas berhasil dihapus!');
    }

    // --- MAJOR CRUD ---
    public function createMajor()
    {
        return view('admin.masterdata.create_major');
    }

    public function storeMajor(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('majors')->insert([
            'name' => trim($request->name),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->route('masterdata.index', ['tab' => 'jurusan'])->with('success', 'Data Jurusan berhasil ditambahkan!');
    }

    public function updateMajor(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        DB::table('majors')->where('id', $id)->update([
            'name' => trim($request->name),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Data Jurusan berhasil diperbarui!');
    }

    public function destroyMajor($id)
    {
        DB::table('majors')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Jurusan berhasil dihapus!');
    }

    public function bulkDestroyAuthor(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('authors')->whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', "{$count} data Penulis terpilih berhasil dihapus!");
        }
        return redirect()->back()->with('error', 'Tidak ada data Penulis yang dipilih.');
    }

    public function bulkDestroyPublisher(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('publishers')->whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', "{$count} data Penerbit terpilih berhasil dihapus!");
        }
        return redirect()->back()->with('error', 'Tidak ada data Penerbit yang dipilih.');
    }

    public function bulkDestroyShelf(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('shelves')->whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', "{$count} data Lokasi Rak terpilih berhasil dihapus!");
        }
        return redirect()->back()->with('error', 'Tidak ada data Lokasi Rak yang dipilih.');
    }

    public function bulkDestroyClass(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('classes')->whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', "{$count} data Kelas terpilih berhasil dihapus!");
        }
        return redirect()->back()->with('error', 'Tidak ada data Kelas yang dipilih.');
    }

    public function bulkDestroyMajor(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $count = DB::table('majors')->whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', "{$count} data Jurusan terpilih berhasil dihapus!");
        }
        return redirect()->back()->with('error', 'Tidak ada data Jurusan yang dipilih.');
    }
}
