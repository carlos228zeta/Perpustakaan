<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Roles Definition
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
        $librarianRoleId = DB::table('roles')->where('name', 'librarian')->value('id');
        $teacherRoleId = DB::table('roles')->where('name', 'teacher')->value('id');
        $studentRoleId = DB::table('roles')->where('name', 'student')->value('id');

        // 2. Default Initial Accounts
        // Admin Account
        $adminUserId = DB::table('users')->insertGetId([
            'name' => 'Admin Utama',
            'email' => 'admin@library.test',
            'password' => Hash::make('password'),
            'role_id' => $adminRoleId,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Librarian Account
        $librarianUserId = DB::table('users')->insertGetId([
            'name' => 'Siti Rahmawati, S.IP',
            'email' => 'librarian@library.test',
            'password' => Hash::make('password'),
            'role_id' => $librarianRoleId,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Teacher Accounts
        $teacherUserId1 = DB::table('users')->insertGetId([
            'name' => 'Budi Santoso, S.Pd',
            'email' => 'budi.teacher@tzuchi.sch.id',
            'password' => Hash::make('password'),
            'role_id' => $teacherRoleId,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('teachers')->insert([
            'user_id' => $teacherUserId1,
            'nip' => '198503152010011002',
            'phone' => '081298765432',
            'subject' => 'Pemrograman Web / PPLG',
            'department' => 'Kejuruan Teknologi',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $teacherUserId2 = DB::table('users')->insertGetId([
            'name' => 'Dewi Lestari, M.Pd',
            'email' => 'dewi.teacher@tzuchi.sch.id',
            'password' => Hash::make('password'),
            'role_id' => $teacherRoleId,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('teachers')->insert([
            'user_id' => $teacherUserId2,
            'nip' => '198807202012022005',
            'phone' => '081387654321',
            'subject' => 'Bahasa Indonesia & Sastra',
            'department' => 'Bahasa & Seni',
            'created_at' => now(), 'updated_at' => now()
        ]);

        // 3. Academic Years & Classes
        $academicYearId = DB::table('academic_years')->insertGetId([
            'name' => '2026/2027', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()
        ]);

        $classList = [
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

        $classMap = [];
        foreach ($classList as $cName) {
            $classMap[$cName] = DB::table('classes')->insertGetId([
                'name' => $cName, 'academic_year_id' => $academicYearId, 'created_at' => now(), 'updated_at' => now()
            ]);
        }

        $classX = $classMap['X PPLG 1'] ?? 1;
        $classXI = $classMap['XI PPLG 1'] ?? 2;
        $classXII = $classMap['XII PPLG 1'] ?? 3;

        // Student Accounts
        $studentUserId1 = DB::table('users')->insertGetId([
            'name' => 'Kevin Pratama',
            'email' => 'kevin.student@tzuchi.sch.id',
            'password' => Hash::make('password'),
            'role_id' => $studentRoleId,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('students')->insert([
            'user_id' => $studentUserId1,
            'nis' => '20261001',
            'nisn' => '0061234567',
            'class_id' => $classX,
            'major' => 'Pengembangan Perangkat Lunak & Gim',
            'phone' => '085712345678',
            'enrollment_year' => 2026,
            'created_at' => now(), 'updated_at' => now()
        ]);

        $studentUserId2 = DB::table('users')->insertGetId([
            'name' => 'Anissa Putri',
            'email' => 'anissa.student@tzuchi.sch.id',
            'password' => Hash::make('password'),
            'role_id' => $studentRoleId,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('students')->insert([
            'user_id' => $studentUserId2,
            'nis' => '20261002',
            'nisn' => '0061234568',
            'class_id' => $classXI,
            'major' => 'Pengembangan Perangkat Lunak & Gim',
            'phone' => '085712345679',
            'enrollment_year' => 2025,
            'created_at' => now(), 'updated_at' => now()
        ]);

        $studentUserId3 = DB::table('users')->insertGetId([
            'name' => 'Michael Wijaya',
            'email' => 'michael.student@tzuchi.sch.id',
            'password' => Hash::make('password'),
            'role_id' => $studentRoleId,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('students')->insert([
            'user_id' => $studentUserId3,
            'nis' => '20261003',
            'nisn' => '0061234569',
            'class_id' => $classXII,
            'major' => 'Pengembangan Perangkat Lunak & Gim',
            'phone' => '085712345680',
            'enrollment_year' => 2024,
            'created_at' => now(), 'updated_at' => now()
        ]);

        // 4. Master Authors
        $authorIds = [];
        $authorsData = [
            ['name' => 'Pramoedya Ananta Toer', 'biography' => 'Sastrawan terkemuka Indonesia penulis Tetralogi Buru.'],
            ['name' => 'Tere Liye', 'biography' => 'Penulis novel populer Indonesia berpengaruh.'],
            ['name' => 'Andrea Hirata', 'biography' => 'Penulis novel Laskar Pelangi yang mendunia.'],
            ['name' => 'Dee Lestari', 'biography' => 'Penulis seri Supernova dan Filosofi Kopi.'],
            ['name' => 'James Clear', 'biography' => 'Pakar pembentukan kebiasaan dan penulis Atomic Habits.'],
            ['name' => 'Robert T. Kiyosaki', 'biography' => 'Pakar keuangaan dan penulis Rich Dad Poor Dad.'],
        ];
        foreach ($authorsData as $auth) {
            $authorIds[] = DB::table('authors')->insertGetId(array_merge($auth, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 5. Master Publishers
        $publisherIds = [];
        $publishersData = [
            ['name' => 'Gramedia Pustaka Utama', 'address' => 'Jakarta Barat', 'phone' => '021-53650110', 'email' => 'redaksi@gramedia.com'],
            ['name' => 'Mizan Pustaka', 'address' => 'Bandung', 'phone' => '022-7834310', 'email' => 'info@mizan.com'],
            ['name' => 'Bentang Pustaka', 'address' => 'Yogyakarta', 'phone' => '0274-886663', 'email' => 'bentang.pustaka@mizan.com'],
            ['name' => 'Republika Penerbit', 'address' => 'Jakarta Selatan', 'phone' => '021-7801316', 'email' => 'penerbit@republika.co.id'],
            ['name' => 'Penerbit Erlangga', 'address' => 'Jakarta Timur', 'phone' => '021-8717006', 'email' => 'webmaster@erlangga.co.id'],
        ];
        foreach ($publishersData as $pub) {
            $publisherIds[] = DB::table('publishers')->insertGetId(array_merge($pub, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 6. Master Shelves
        $shelfIds = [];
        $shelvesData = [
            ['code' => 'R-A01', 'name' => 'Rak Sastra & Fiksi', 'location' => 'Lantai 1 - Sayap Kiri', 'description' => 'Koleksi novel, sastra, dan fiksi populer.'],
            ['code' => 'R-A02', 'name' => 'Rak Teknologi & PPLG', 'location' => 'Lantai 1 - Sayap Kanan', 'description' => 'Koleksi pemrograman, komputer, dan teknologi.'],
            ['code' => 'R-B01', 'name' => 'Rak Sains & Matematika', 'location' => 'Lantai 2 - Ruang Utama', 'description' => 'Buku pelajaran dan eksperimen sains.'],
            ['code' => 'R-B02', 'name' => 'Rak Pengembangan Diri', 'location' => 'Lantai 2 - Pojok Baca', 'description' => 'Buku psikologi dan self-development.'],
            ['code' => 'R-C01', 'name' => 'Rak Referensi Utama', 'location' => 'Lantai 1 - Tengah', 'description' => 'Ensiklopedi, kamus, dan referensi.'],
        ];
        foreach ($shelvesData as $sh) {
            $shelfIds[] = DB::table('shelves')->insertGetId(array_merge($sh, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 7. Categories
        $categoryIds = [];
        $categoriesData = [
            ['name' => 'Teknologi & PPLG', 'description' => 'Buku bidang pemrograman, software, dan teknologi.'],
            ['name' => 'Sains & Matematika', 'description' => 'Ilmu pengetahuan alam, fisika, dan matematika.'],
            ['name' => 'Fiksi & Novel', 'description' => 'Koleksi novel sastra, fiksi, dan cerita menarik.'],
            ['name' => 'Pengembangan Diri', 'description' => 'Buku motivasi, kebiasaan, dan kepemimpinan.'],
            ['name' => 'Sejarah & Budaya', 'description' => 'Sejarah nasional, dunia, dan kebudayaan.'],
            ['name' => 'Referensi', 'description' => 'Buku referensi, ensiklopedia, dan kamus.'],
        ];
        foreach ($categoriesData as $cat) {
            $categoryIds[] = DB::table('categories')->insertGetId([
                'name' => $cat['name'], 'slug' => Str::slug($cat['name']), 'description' => $cat['description'],
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        // 8. Books & Book Copies
        $booksData = [
            [
                'isbn' => '9789799731234',
                'title' => 'Bumi Manusia',
                'synopsis' => 'Kisah Minke di era kolonial Hindia Belanda yang memperjuangkan keadilan dan cinta.',
                'category_id' => $categoryIds[2],
                'author_id' => $authorIds[0],
                'publisher_id' => $publisherIds[0],
                'shelf_id' => $shelfIds[0],
                'publication_year' => 2011,
                'copies_prefix' => 'BM',
                'total_copies' => 5,
            ],
            [
                'isbn' => '9789793062792',
                'title' => 'Laskar Pelangi',
                'synopsis' => 'Kisah persahabatan 10 anak Belitung yang berjuang menuntut ilmu di SD Muhammadiyah.',
                'category_id' => $categoryIds[2],
                'author_id' => $authorIds[2],
                'publisher_id' => $publisherIds[2],
                'shelf_id' => $shelfIds[0],
                'publication_year' => 2005,
                'copies_prefix' => 'LP',
                'total_copies' => 4,
            ],
            [
                'isbn' => '9786020633176',
                'title' => 'Atomic Habits',
                'synopsis' => 'Perubahan kecil yang memberikan hasil luar biasa dalam membangun kebiasaan positif.',
                'category_id' => $categoryIds[3],
                'author_id' => $authorIds[4],
                'publisher_id' => $publisherIds[0],
                'shelf_id' => $shelfIds[3],
                'publication_year' => 2019,
                'copies_prefix' => 'AH',
                'total_copies' => 5,
            ],
            [
                'isbn' => '9786020330051',
                'title' => 'Rich Dad Poor Dad',
                'synopsis' => 'Pelajaran keuangan berharga tentang cara pandang kaya dan miskin terhadap uang.',
                'category_id' => $categoryIds[3],
                'author_id' => $authorIds[5],
                'publisher_id' => $publisherIds[0],
                'shelf_id' => $shelfIds[3],
                'publication_year' => 2016,
                'copies_prefix' => 'RD',
                'total_copies' => 4,
            ],
            [
                'isbn' => '9786230101234',
                'title' => 'Pemrograman Web Modern dengan Laravel',
                'synopsis' => 'Panduan praktis membangun aplikasi web berbasis Laravel dan JavaScript modern.',
                'category_id' => $categoryIds[0],
                'author_id' => $authorIds[1],
                'publisher_id' => $publisherIds[4],
                'shelf_id' => $shelfIds[1],
                'publication_year' => 2023,
                'copies_prefix' => 'PW',
                'total_copies' => 4,
            ],
            [
                'isbn' => '9786024246945',
                'title' => 'Filosofi Teras',
                'synopsis' => 'Penerapan filsafat Stoikisme kuno untuk mental tangguh di era modern.',
                'category_id' => $categoryIds[3],
                'author_id' => $authorIds[3],
                'publisher_id' => $publisherIds[1],
                'shelf_id' => $shelfIds[3],
                'publication_year' => 2018,
                'copies_prefix' => 'FT',
                'total_copies' => 3,
            ],
        ];

        $createdBookCopyIds = [];

        foreach ($booksData as $bData) {
            $prefix = $bData['copies_prefix'];
            $totalCount = $bData['total_copies'];
            unset($bData['copies_prefix'], $bData['total_copies']);

            $bookId = DB::table('books')->insertGetId(array_merge($bData, [
                'slug' => Str::slug($bData['title']),
                'language' => 'Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            // Create copies
            for ($i = 1; $i <= $totalCount; $i++) {
                $copyCode = sprintf('%s-%03d', $prefix, $i);
                $copyId = DB::table('book_copies')->insertGetId([
                    'book_id' => $bookId,
                    'copy_code' => $copyCode,
                    'procurement_date' => now()->subMonths(rand(1, 12))->format('Y-m-d'),
                    'condition' => 'good',
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $createdBookCopyIds[$prefix . '_' . $i] = $copyId;
            }
        }

        // 9. Realistic Borrowings & Fines
        // Active Borrowing 1 (Kevin - Laskar Pelangi LP-001)
        if (isset($createdBookCopyIds['LP_001'])) {
            $borrowingId1 = DB::table('borrowings')->insertGetId([
                'user_id' => $studentUserId1,
                'approved_by' => $librarianUserId,
                'borrow_date' => now()->subDays(3)->format('Y-m-d'),
                'due_date' => now()->addDays(4)->format('Y-m-d'),
                'status' => 'borrowed',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ]);
            DB::table('borrowing_items')->insert([
                'borrowing_id' => $borrowingId1,
                'book_copy_id' => $createdBookCopyIds['LP_001'],
                'created_at' => now(), 'updated_at' => now()
            ]);
            DB::table('book_copies')->where('id', $createdBookCopyIds['LP_001'])->update(['status' => 'borrowed']);
        }

        // Active Borrowing 2 (Guru Budi - Pemrograman Web PW-001)
        if (isset($createdBookCopyIds['PW_001'])) {
            $borrowingId2 = DB::table('borrowings')->insertGetId([
                'user_id' => $teacherUserId1,
                'approved_by' => $adminUserId,
                'borrow_date' => now()->subDays(5)->format('Y-m-d'),
                'due_date' => now()->addDays(9)->format('Y-m-d'),
                'status' => 'borrowed',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]);
            DB::table('borrowing_items')->insert([
                'borrowing_id' => $borrowingId2,
                'book_copy_id' => $createdBookCopyIds['PW_001'],
                'created_at' => now(), 'updated_at' => now()
            ]);
            DB::table('book_copies')->where('id', $createdBookCopyIds['PW_001'])->update(['status' => 'borrowed']);
        }

        // Overdue Borrowing with Unpaid Fine (Anissa - Filosofi Teras FT-001, Overdue 5 days)
        if (isset($createdBookCopyIds['FT_001'])) {
            $borrowingId3 = DB::table('borrowings')->insertGetId([
                'user_id' => $studentUserId2,
                'approved_by' => $librarianUserId,
                'borrow_date' => now()->subDays(12)->format('Y-m-d'),
                'due_date' => now()->subDays(5)->format('Y-m-d'),
                'status' => 'borrowed',
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ]);
            DB::table('borrowing_items')->insert([
                'borrowing_id' => $borrowingId3,
                'book_copy_id' => $createdBookCopyIds['FT_001'],
                'created_at' => now(), 'updated_at' => now()
            ]);
            DB::table('book_copies')->where('id', $createdBookCopyIds['FT_001'])->update(['status' => 'borrowed']);

            // Insert Fine Record
            DB::table('fines')->insert([
                'borrowing_id' => $borrowingId3,
                'user_id' => $studentUserId2,
                'amount' => 5000,
                'reason' => 'Keterlambatan pengembalian 5 hari',
                'status' => 'unpaid',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]);
        }

        // Returned Borrowing with Paid Fine (Michael - Bumi Manusia BM-001)
        if (isset($createdBookCopyIds['BM_001'])) {
            $borrowingId4 = DB::table('borrowings')->insertGetId([
                'user_id' => $studentUserId3,
                'approved_by' => $librarianUserId,
                'returned_to' => $librarianUserId,
                'borrow_date' => now()->subDays(10)->format('Y-m-d'),
                'due_date' => now()->subDays(3)->format('Y-m-d'),
                'return_date' => now()->subDays(1)->format('Y-m-d'),
                'status' => 'returned',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(1),
            ]);
            DB::table('borrowing_items')->insert([
                'borrowing_id' => $borrowingId4,
                'book_copy_id' => $createdBookCopyIds['BM_001'],
                'created_at' => now(), 'updated_at' => now()
            ]);

            DB::table('fines')->insert([
                'borrowing_id' => $borrowingId4,
                'user_id' => $studentUserId3,
                'amount' => 2000,
                'reason' => 'Keterlambatan pengembalian 2 hari',
                'status' => 'paid',
                'paid_at' => now()->subDays(1),
                'processed_by' => $librarianUserId,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(1),
            ]);
        }

        // 10. Reservations & Activity Logs
        $atomicHabitsBookId = DB::table('books')->where('isbn', '9786020633176')->value('id');
        if ($atomicHabitsBookId) {
            DB::table('reservations')->insert([
                'user_id' => $studentUserId1,
                'book_id' => $atomicHabitsBookId,
                'status' => 'waiting',
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ]);
        }

        // Seed Activity Logs
        $logs = [
            ['user_id' => $librarianUserId, 'activity' => 'Memproses transaksi peminjaman baru (Kevin Pratama - Laskar Pelangi)', 'module' => 'Peminjaman'],
            ['user_id' => $adminUserId, 'activity' => 'Menyetujui peminjaman buku (Budi Santoso - Pemrograman Web Modern)', 'module' => 'Peminjaman'],
            ['user_id' => $librarianUserId, 'activity' => 'Menerima pembayaran denda Rp 2.000 (Michael Wijaya)', 'module' => 'Denda'],
            ['user_id' => $studentUserId1, 'activity' => 'Melakukan pengajuan reservasi buku Atomic Habits', 'module' => 'Reservasi'],
        ];
        foreach ($logs as $lg) {
            DB::table('activity_logs')->insert(array_merge($lg, [
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subMinutes(rand(5, 120)),
                'updated_at' => now(),
            ]));
        }

        // 11. Operational Settings
        $settings = [
            ['key' => 'max_student_borrow', 'value' => '3'],
            ['key' => 'max_teacher_borrow', 'value' => '5'],
            ['key' => 'student_borrow_days', 'value' => '7'],
            ['key' => 'teacher_borrow_days', 'value' => '14'],
            ['key' => 'fine_per_day', 'value' => '1000'],
            ['key' => 'institution_name', 'value' => 'Sekolah Cinta Kasih Tzu Chi'],
            ['key' => 'app_title', 'value' => 'LMS Tzu Chi'],
            ['key' => 'app_subtitle', 'value' => 'Perpustakaan Cengkareng'],
        ];
        foreach ($settings as $st) {
            DB::table('library_settings')->updateOrInsert(['key' => $st['key']], array_merge($st, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
