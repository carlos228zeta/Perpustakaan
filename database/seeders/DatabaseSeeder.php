<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Roles
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'librarian', 'display_name' => 'Petugas Perpustakaan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'teacher', 'display_name' => 'Guru', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'student', 'display_name' => 'Siswa', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(['name' => $r['name']], $r);
        }

        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        // 2. Default Initial Admin Account
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@library.test'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 3. Academic Years & Classes
        $academicYearId = DB::table('academic_years')->insertGetId([
            'name' => '2026/2027', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()
        ]);
        
        foreach (['X PPLG 1', 'XI PPLG 1', 'XII PPLG 1'] as $className) {
            DB::table('classes')->insert([
                'name' => $className, 'academic_year_id' => $academicYearId,
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        // 4. Master Shelves & Categories
        foreach (['R-A01', 'R-A02', 'R-B01', 'R-B02', 'R-C01'] as $shelfCode) {
            DB::table('shelves')->insert([
                'code' => $shelfCode, 'name' => 'Rak Utama ' . $shelfCode, 
                'location' => 'Lantai 1 Perpustakaan', 'description' => 'Rak Koleksi Buku Utama',
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        $categories = ['Pendidikan', 'Teknologi', 'Sains', 'Fiksi', 'Non-Fiksi', 'Bahasa', 'Sejarah', 'Agama', 'Referensi', 'Komputer'];
        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name' => $cat, 'slug' => Str::slug($cat), 'description' => 'Kategori buku ' . $cat,
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        // 5. Library Operational Settings
        $settings = [
            ['key' => 'max_student_borrow', 'value' => '3'],
            ['key' => 'max_teacher_borrow', 'value' => '5'],
            ['key' => 'student_borrow_days', 'value' => '7'],
            ['key' => 'teacher_borrow_days', 'value' => '14'],
            ['key' => 'fine_per_day', 'value' => '1000'],
            ['key' => 'institution_name', 'value' => 'Cinta Kasih Tzu Chi Cengkareng'],
            ['key' => 'app_name', 'value' => 'Library Management System'],
        ];
        foreach ($settings as $st) {
            DB::table('library_settings')->updateOrInsert(['key' => $st['key']], array_merge($st, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
