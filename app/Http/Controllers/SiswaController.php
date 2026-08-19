<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $class_id = $request->input('class_id');

        $query = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->select('students.*', 'users.name', 'users.email', 'users.status as user_status', 'classes.name as class_name');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('students.nis', 'like', "%{$search}%")
                  ->orWhere('students.nisn', 'like', "%{$search}%");
            });
        }

        if ($class_id) {
            $query->where('students.class_id', $class_id);
        }

        $students = $query->orderBy('users.name', 'asc')->paginate(10);
        $classes = DB::table('classes')->get();

        return view('siswa.index', compact('students', 'search', 'classes', 'class_id'));
    }

    public function create()
    {
        $classes = DB::table('classes')->get();
        $majors = DB::table('majors')->orderBy('name')->get();
        return view('siswa.create', compact('classes', 'majors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'nis' => 'required|string|unique:students,nis',
            'nisn' => 'nullable|string|unique:students,nisn',
            'class_id' => 'nullable|exists:classes,id',
            'major' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $studentRoleId = DB::table('roles')->where('name', 'student')->value('id');

        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $studentRoleId,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('students')->insert([
            'user_id' => $userId,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'class_id' => $request->class_id,
            'major' => $request->major ?? 'PPLG',
            'phone' => $request->phone,
            'enrollment_year' => date('Y'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $student = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*', 'users.name', 'users.email')
            ->where('students.id', $id)
            ->first();

        if (!$student) {
            return redirect()->route('siswa.index')->with('error', 'Siswa tidak ditemukan.');
        }

        $classes = DB::table('classes')->get();
        $majors = DB::table('majors')->orderBy('name')->get();

        return view('siswa.edit', compact('student', 'classes', 'majors'));
    }

    public function update(Request $request, $id)
    {
        $student = DB::table('students')->where('id', $id)->first();
        if (!$student) {
            return redirect()->route('siswa.index')->with('error', 'Data siswa tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'nis' => 'required|string|unique:students,nis,' . $id,
            'class_id' => 'nullable|exists:classes,id',
            'phone' => 'nullable|string|max:20',
        ]);

        DB::table('users')->where('id', $student->user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ]);

        if ($request->filled('password')) {
            DB::table('users')->where('id', $student->user_id)->update([
                'password' => Hash::make($request->password)
            ]);
        }

        DB::table('students')->where('id', $id)->update([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'class_id' => $request->class_id,
            'major' => $request->major,
            'phone' => $request->phone,
            'updated_at' => now(),
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $student = DB::table('students')->where('id', $id)->first();
        if ($student) {
            DB::table('users')->where('id', $student->user_id)->delete();
            DB::table('students')->where('id', $id)->delete();
        }
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus!');
    }

    public function importTemplate()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=template_import_siswa.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Nama', 'Email', 'Password', 'NIS', 'NISN', 'ID_Kelas', 'Jurusan', 'No_HP'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Explicitly set delimiter to semicolon for Indonesian Excel locale
            fputcsv($file, $columns, ';');
            
            // Example row
            fputcsv($file, ['Budi Santoso', 'budi@siswa.com', 'password123', '1001', '0012345678', '1', 'PPLG', '08123456789'], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        // Read file and parse CSV, supporting both comma and semicolon
        $content = file_get_contents($path);
        $delimiter = strpos($content, ';') !== false ? ';' : ',';
        
        $data = array_map(function($line) use ($delimiter) {
            return str_getcsv($line, $delimiter);
        }, file($path));
        
        // Remove header row
        $header = array_shift($data);
        
        // Simple validation to ensure columns match (at least 8 columns)
        if (count($header) < 8) {
            return redirect()->back()->with('error', 'Format file CSV tidak sesuai dengan template. Pastikan ada 8 kolom (Nama, Email, dll).');
        }

        $studentRoleId = DB::table('roles')->where('name', 'student')->value('id');
        
        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($data as $row) {
                // Skip empty rows
                if (empty(trim($row[0]))) continue;
                
                $nama = trim($row[0]);
                $email = trim($row[1]);
                $password = trim($row[2]) ?: 'cintakasih123';
                $nis = trim($row[3]);
                $nisn = trim($row[4] ?? '');
                $id_kelas = trim($row[5] ?? '');
                $jurusan = trim($row[6] ?? '') ?: 'Umum';
                $no_hp = trim($row[7] ?? '');

                // Check if email or nis already exists
                $existingUser = DB::table('users')->where('email', $email)->exists();
                $existingStudent = DB::table('students')->where('nis', $nis)->exists();
                
                if ($existingUser || $existingStudent) {
                    continue; // Skip existing data
                }

                $userId = DB::table('users')->insertGetId([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role_id' => $studentRoleId,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('students')->insert([
                    'user_id' => $userId,
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'class_id' => is_numeric($id_kelas) ? $id_kelas : null,
                    'major' => $jurusan,
                    'phone' => $no_hp,
                    'enrollment_year' => date('Y'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $count++;
            }
            DB::commit();
            return redirect()->route('siswa.index')->with('success', "Berhasil mengimpor $count data siswa baru!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
