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
            ['id' => 1, 'name' => 'admin', 'display_name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'librarian', 'display_name' => 'Petugas Perpustakaan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'student', 'display_name' => 'Siswa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'teacher', 'display_name' => 'Guru', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(['id' => $r['id']], $r);
        }

        $adminRoleId = 1;
        $librarianRoleId = 2;
        $studentRoleId = 3;
        $teacherRoleId = 4;

        // 2. Default Initial Accounts
        // Admin
        $adminUser = DB::table('users')->where('email', 'admin@library.test')->first();
        if (!$adminUser) {
            DB::table('users')->insert([
                'name' => 'Admin Utama',
                'email' => 'admin@library.test',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Librarian
        $librarianUser = DB::table('users')->where('email', 'librarian@library.test')->first();
        if (!$librarianUser) {
            DB::table('users')->insert([
                'name' => 'Siti Rahmawati, S.IP',
                'email' => 'librarian@library.test',
                'password' => Hash::make('password'),
                'role_id' => $librarianRoleId,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 3. Academic Years & Classes
        $academicYear = DB::table('academic_years')->first();
        if (!$academicYear) {
            $academicYearId = DB::table('academic_years')->insertGetId([
                'name' => '2026/2027', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()
            ]);
        } else {
            $academicYearId = $academicYear->id;
        }

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
            $existingClass = DB::table('classes')->where('name', $cName)->first();
            if ($existingClass) {
                $classMap[$cName] = $existingClass->id;
            } else {
                $classMap[$cName] = DB::table('classes')->insertGetId([
                    'name' => $cName, 'academic_year_id' => $academicYearId, 'created_at' => now(), 'updated_at' => now()
                ]);
            }
        }

        // 4. Majors Definition
        $majorList = [
            'MIPA (Matematika dan Ilmu Pengetahuan Alam)',
            'IPS (Ilmu Pengetahuan Sosial)',
            'Bahasa dan Budaya',
            'PPLG (Pengembangan Perangkat Lunak dan Gim)',
            'AKL (Akuntansi dan Keuangan Lembaga)',
            'MPLB (Manajemen Perkantoran dan Layanan Bisnis)',
            'OTKP (Otomatisasi & Tata Kelola Perkantoran)',
            'Umum (SMP)',
        ];

        foreach ($majorList as $mName) {
            DB::table('majors')->updateOrInsert(
                ['name' => $mName],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // 5. Guru (Teachers) - Mobile Legends Heroes
        $teachersData = [
            [
                'name' => 'Tigreal Wibowo, S.Pd',
                'email' => 'tigreal.teacher@tzuchi.sch.id',
                'nip' => '198203152008011001',
                'phone' => '081211112201',
                'subject' => 'Pendidikan Pancasila & Kewarganegaraan',
                'department' => 'Sosial & Humaniora',
            ],
            [
                'name' => 'Estes Wijaya, M.Kom',
                'email' => 'estes.teacher@tzuchi.sch.id',
                'nip' => '198507202010021002',
                'phone' => '081211112202',
                'subject' => 'Pemrograman Web & Basis Data (PPLG)',
                'department' => 'Kejuruan Teknologi Informasi',
            ],
            [
                'name' => 'Rafaela Putri, S.Si, M.Pd',
                'email' => 'rafaela.teacher@tzuchi.sch.id',
                'nip' => '198904122012022003',
                'phone' => '081211112203',
                'subject' => 'Biologi & Ilmu Pengetahuan Alam',
                'department' => 'MIPA & Sains',
            ],
            [
                'name' => 'Franco Haryanto, S.Pd',
                'email' => 'franco.teacher@tzuchi.sch.id',
                'nip' => '198411082009011004',
                'phone' => '081211112204',
                'subject' => 'Pendidikan Jasmani & Olahraga',
                'department' => 'Olahraga & Kesehatan',
            ],
            [
                'name' => 'Johnson Santoso, M.T',
                'email' => 'johnson.teacher@tzuchi.sch.id',
                'nip' => '198709252011011005',
                'phone' => '081211112205',
                'subject' => 'Infrastruktur Jaringan & Cloud',
                'department' => 'Kejuruan Teknologi Informasi',
            ],
            [
                'name' => 'Lolita Anggraini, S.E, M.Ak',
                'email' => 'lolita.teacher@tzuchi.sch.id',
                'nip' => '199002182014022006',
                'phone' => '081211112206',
                'subject' => 'Akuntansi Keuangan & Perpajakan',
                'department' => 'Kejuruan Akuntansi (AKL)',
            ],
            [
                'name' => 'Minotaur Siregar, S.Pd',
                'email' => 'minotaur.teacher@tzuchi.sch.id',
                'nip' => '198306142007011007',
                'phone' => '081211112207',
                'subject' => 'Sejarah Indonesia & Kebudayaan',
                'department' => 'Sosial & Humaniora',
            ],
            [
                'name' => 'Belerick Kusuma, S.Pd',
                'email' => 'belerick.teacher@tzuchi.sch.id',
                'nip' => '198610052010011008',
                'phone' => '081211112208',
                'subject' => 'Geografi & Konservasi Lingkungan',
                'department' => 'MIPA & Sains',
            ],
        ];

        foreach ($teachersData as $t) {
            $user = DB::table('users')->where('email', $t['email'])->first();
            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'name' => $t['name'],
                    'email' => $t['email'],
                    'password' => Hash::make('password'),
                    'role_id' => $teacherRoleId,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $userId = $user->id;
            }

            DB::table('teachers')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'nip' => $t['nip'],
                    'phone' => $t['phone'],
                    'subject' => $t['subject'],
                    'department' => $t['department'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 6. Siswa (Students) - Mobile Legends Heroes
        $studentsData = [
            // SMP Students
            [
                'name' => 'Nana Maharani',
                'email' => 'nana.student@tzuchi.sch.id',
                'nis' => '20260701',
                'nisn' => '0081112233',
                'class' => '7A',
                'major' => 'Umum (SMP)',
                'phone' => '085700010001',
                'year' => 2026,
            ],
            [
                'name' => 'Harley Pratama',
                'email' => 'harley.student@tzuchi.sch.id',
                'nis' => '20260702',
                'nisn' => '0081112234',
                'class' => '7B',
                'major' => 'Umum (SMP)',
                'phone' => '085700010002',
                'year' => 2026,
            ],
            [
                'name' => 'Chang\'e Anggraini',
                'email' => 'change.student@tzuchi.sch.id',
                'nis' => '20250801',
                'nisn' => '0071112235',
                'class' => '8A',
                'major' => 'Umum (SMP)',
                'phone' => '085700010003',
                'year' => 2025,
            ],
            [
                'name' => 'Lylia Oktavia',
                'email' => 'lylia.student@tzuchi.sch.id',
                'nis' => '20250802',
                'nisn' => '0071112236',
                'class' => '8C',
                'major' => 'Umum (SMP)',
                'phone' => '085700010004',
                'year' => 2025,
            ],
            [
                'name' => 'Cyclops Setiawan',
                'email' => 'cyclops.student@tzuchi.sch.id',
                'nis' => '20240901',
                'nisn' => '0061112237',
                'class' => '9A',
                'major' => 'Umum (SMP)',
                'phone' => '085700010005',
                'year' => 2024,
            ],
            [
                'name' => 'Diggie Purnomo',
                'email' => 'diggie.student@tzuchi.sch.id',
                'nis' => '20240902',
                'nisn' => '0061112238',
                'class' => '9B',
                'major' => 'Umum (SMP)',
                'phone' => '085700010006',
                'year' => 2024,
            ],

            // SMA Students
            [
                'name' => 'Kagura Ayudisha',
                'email' => 'kagura.student@tzuchi.sch.id',
                'nis' => '20261011',
                'nisn' => '0061112240',
                'class' => 'X.1',
                'major' => 'MIPA (Matematika dan Ilmu Pengetahuan Alam)',
                'phone' => '085700010011',
                'year' => 2026,
            ],
            [
                'name' => 'Hayabusa Kenji',
                'email' => 'hayabusa.student@tzuchi.sch.id',
                'nis' => '20261012',
                'nisn' => '0061112241',
                'class' => 'X.2',
                'major' => 'MIPA (Matematika dan Ilmu Pengetahuan Alam)',
                'phone' => '085700010012',
                'year' => 2026,
            ],
            [
                'name' => 'Xavier Jonathan',
                'email' => 'xavier.student@tzuchi.sch.id',
                'nis' => '20251101',
                'nisn' => '0051112242',
                'class' => 'XI.1',
                'major' => 'MIPA (Matematika dan Ilmu Pengetahuan Alam)',
                'phone' => '085700010013',
                'year' => 2025,
            ],
            [
                'name' => 'Lunox Gabriella',
                'email' => 'lunox.student@tzuchi.sch.id',
                'nis' => '20251102',
                'nisn' => '0051112243',
                'class' => 'XI.2',
                'major' => 'IPS (Ilmu Pengetahuan Sosial)',
                'phone' => '085700010014',
                'year' => 2025,
            ],
            [
                'name' => 'Cecilion Alexander',
                'email' => 'cecilion.student@tzuchi.sch.id',
                'nis' => '20241201',
                'nisn' => '0041112244',
                'class' => 'XII.1',
                'major' => 'Bahasa dan Budaya',
                'phone' => '085700010015',
                'year' => 2024,
            ],
            [
                'name' => 'Carmilla Vanessa',
                'email' => 'carmilla.student@tzuchi.sch.id',
                'nis' => '20241202',
                'nisn' => '0041112245',
                'class' => 'XII.2',
                'major' => 'Bahasa dan Budaya',
                'phone' => '085700010016',
                'year' => 2024,
            ],

            // SMK Students
            [
                'name' => 'Chou Daniswara',
                'email' => 'chou.student@tzuchi.sch.id',
                'nis' => '20262001',
                'nisn' => '0062223340',
                'class' => 'X PPLG 1',
                'major' => 'PPLG (Pengembangan Perangkat Lunak dan Gim)',
                'phone' => '085700010021',
                'year' => 2026,
            ],
            [
                'name' => 'Gusion Raymond',
                'email' => 'gusion.student@tzuchi.sch.id',
                'nis' => '20262002',
                'nisn' => '0062223341',
                'class' => 'X PPLG 2',
                'major' => 'PPLG (Pengembangan Perangkat Lunak dan Gim)',
                'phone' => '085700010022',
                'year' => 2026,
            ],
            [
                'name' => 'Lesley Kirana',
                'email' => 'lesley.student@tzuchi.sch.id',
                'nis' => '20262003',
                'nisn' => '0062223342',
                'class' => 'X AKL',
                'major' => 'AKL (Akuntansi dan Keuangan Lembaga)',
                'phone' => '085700010023',
                'year' => 2026,
            ],
            [
                'name' => 'Layla Clarissa',
                'email' => 'layla.student@tzuchi.sch.id',
                'nis' => '20262004',
                'nisn' => '0062223343',
                'class' => 'X MPLB',
                'major' => 'MPLB (Manajemen Perkantoran dan Layanan Bisnis)',
                'phone' => '085700010024',
                'year' => 2026,
            ],
            [
                'name' => 'Fanny Alisya',
                'email' => 'fanny.student@tzuchi.sch.id',
                'nis' => '20252101',
                'nisn' => '0052223344',
                'class' => 'XI PPLG 1',
                'major' => 'PPLG (Pengembangan Perangkat Lunak dan Gim)',
                'phone' => '085700010025',
                'year' => 2025,
            ],
            [
                'name' => 'Ling Raditya',
                'email' => 'ling.student@tzuchi.sch.id',
                'nis' => '20252102',
                'nisn' => '0052223345',
                'class' => 'XI PPLG 2',
                'major' => 'PPLG (Pengembangan Perangkat Lunak dan Gim)',
                'phone' => '085700010026',
                'year' => 2025,
            ],
            [
                'name' => 'Claude Pratama',
                'email' => 'claude.student@tzuchi.sch.id',
                'nis' => '20252103',
                'nisn' => '0052223346',
                'class' => 'XI AKL',
                'major' => 'AKL (Akuntansi dan Keuangan Lembaga)',
                'phone' => '085700010027',
                'year' => 2025,
            ],
            [
                'name' => 'Beatrix Anastasia',
                'email' => 'beatrix.student@tzuchi.sch.id',
                'nis' => '20252104',
                'nisn' => '0052223347',
                'class' => 'XI MPLB',
                'major' => 'MPLB (Manajemen Perkantoran dan Layanan Bisnis)',
                'phone' => '085700010028',
                'year' => 2025,
            ],
            [
                'name' => 'Lancelot Barra',
                'email' => 'lancelot.student@tzuchi.sch.id',
                'nis' => '20242201',
                'nisn' => '0042223348',
                'class' => 'XII PPLG 1',
                'major' => 'PPLG (Pengembangan Perangkat Lunak dan Gim)',
                'phone' => '085700010029',
                'year' => 2024,
            ],
            [
                'name' => 'Odette Aurelia',
                'email' => 'odette.student@tzuchi.sch.id',
                'nis' => '20242202',
                'nisn' => '0042223349',
                'class' => 'XII PPLG 2',
                'major' => 'PPLG (Pengembangan Perangkat Lunak dan Gim)',
                'phone' => '085700010030',
                'year' => 2024,
            ],
            [
                'name' => 'Bruno Valentino',
                'email' => 'bruno.student@tzuchi.sch.id',
                'nis' => '20242203',
                'nisn' => '0042223350',
                'class' => 'XII AKL',
                'major' => 'AKL (Akuntansi dan Keuangan Lembaga)',
                'phone' => '085700010031',
                'year' => 2024,
            ],
            [
                'name' => 'Clint Gunawan',
                'email' => 'clint.student@tzuchi.sch.id',
                'nis' => '20242204',
                'nisn' => '0042223351',
                'class' => 'XII OTKP',
                'major' => 'OTKP (Otomatisasi & Tata Kelola Perkantoran)',
                'phone' => '085700010032',
                'year' => 2024,
            ],
        ];

        foreach ($studentsData as $s) {
            $user = DB::table('users')->where('email', $s['email'])->first();
            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'name' => $s['name'],
                    'email' => $s['email'],
                    'password' => Hash::make('password'),
                    'role_id' => $studentRoleId,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $userId = $user->id;
            }

            $cId = $classMap[$s['class']] ?? 1;

            DB::table('students')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'nis' => $s['nis'],
                    'nisn' => $s['nisn'],
                    'class_id' => $cId,
                    'major' => $s['major'],
                    'phone' => $s['phone'],
                    'enrollment_year' => $s['year'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 7. Master Authors
        $authorsData = [
            ['name' => 'Gord Sang Peneliti Arkana', 'biography' => 'Pakar algoritma, magis sains, dan teknologi komputasi tinggi.'],
            ['name' => 'Novaria Astronesia', 'biography' => 'Peneliti kosmologi antariksa dan perancangan grafis interaktif.'],
            ['name' => 'Zhask V. Void', 'biography' => 'Penulis ensiklopedia sains biologi dan ekosistem luar angkasa.'],
            ['name' => 'Natan Timekeeper', 'biography' => 'Fisikawan teoretis penemu konsep relativitas ruang dan waktu.'],
            ['name' => 'Aurora Frost, Ph.D', 'biography' => 'Sejarawan dan peneliti mitologi kuno Land of Dawn.'],
            ['name' => 'Kagura Onmyouji', 'biography' => 'Pujangga sastra klasik dan pengkaji budaya Cadia Riverlands.'],
            ['name' => 'James Clear', 'biography' => 'Pakar produktivitas dan pembentukan kebiasaan positif.'],
            ['name' => 'Andrea Hirata', 'biography' => 'Sastrawan terkemuka penulis novel inspiratif pendidikan.'],
        ];

        $authorIds = [];
        foreach ($authorsData as $auth) {
            $existing = DB::table('authors')->where('name', $auth['name'])->first();
            if ($existing) {
                $authorIds[$auth['name']] = $existing->id;
            } else {
                $authorIds[$auth['name']] = DB::table('authors')->insertGetId(array_merge($auth, ['created_at' => now(), 'updated_at' => now()]));
            }
        }

        // 8. Master Publishers
        $publishersData = [
            ['name' => 'Moniyan Empire Publishing', 'address' => 'Kota Pusat Moniyan', 'phone' => '021-5550101', 'email' => 'editorial@moniyan.org'],
            ['name' => 'Eruditio Scholar Press', 'address' => 'Kota Teknologi Eruditio', 'phone' => '021-5550102', 'email' => 'scholar@eruditio.edu'],
            ['name' => 'Cadia Riverlands Media', 'address' => 'Lembah Sungai Cadia', 'phone' => '021-5550103', 'email' => 'pustaka@cadia.net'],
            ['name' => 'Gramedia Pustaka Utama', 'address' => 'Jakarta Barat', 'phone' => '021-53650110', 'email' => 'redaksi@gramedia.com'],
            ['name' => 'Penerbit Erlangga', 'address' => 'Jakarta Timur', 'phone' => '021-8717006', 'email' => 'webmaster@erlangga.co.id'],
        ];

        $publisherIds = [];
        foreach ($publishersData as $pub) {
            $existing = DB::table('publishers')->where('name', $pub['name'])->first();
            if ($existing) {
                $publisherIds[$pub['name']] = $existing->id;
            } else {
                $publisherIds[$pub['name']] = DB::table('publishers')->insertGetId(array_merge($pub, ['created_at' => now(), 'updated_at' => now()]));
            }
        }

        // 9. Master Shelves
        $shelvesData = [
            ['code' => 'R-A01', 'name' => 'Rak Sastra & Fantasi', 'location' => 'Lantai 1 - Sayap Kiri', 'description' => 'Koleksi novel, sastra, dan fiksi populer.'],
            ['code' => 'R-A02', 'name' => 'Rak Teknologi, Coding & PPLG', 'location' => 'Lantai 1 - Sayap Kanan', 'description' => 'Koleksi pemrograman, kecerdasan buatan, dan game.'],
            ['code' => 'R-B01', 'name' => 'Rak Sains & Fisika Alam', 'location' => 'Lantai 2 - Ruang Utama', 'description' => 'Buku sains, fisika modern, matematika, dan biologi.'],
            ['code' => 'R-B02', 'name' => 'Rak Kepemimpinan & Strategi', 'location' => 'Lantai 2 - Pojok Diskusi', 'description' => 'Buku strategi, taktik tim, dan pengembangan kepribadian.'],
            ['code' => 'R-C01', 'name' => 'Rak Bisnis, Akuntansi & Perkantoran', 'location' => 'Lantai 1 - Tengah', 'description' => 'Buku akuntansi, perpajakan, dan tata kelola kantor.'],
            ['code' => 'R-C02', 'name' => 'Rak Sejarah & Ensiklopedi', 'location' => 'Lantai 2 - Ruang Referensi', 'description' => 'Buku sejarah peradaban, mitologi, dan ensiklopedia.'],
        ];

        $shelfIds = [];
        foreach ($shelvesData as $sh) {
            $existing = DB::table('shelves')->where('code', $sh['code'])->first();
            if ($existing) {
                $shelfIds[$sh['code']] = $existing->id;
            } else {
                $shelfIds[$sh['code']] = DB::table('shelves')->insertGetId(array_merge($sh, ['created_at' => now(), 'updated_at' => now()]));
            }
        }

        // 10. Categories
        $categoriesData = [
            ['name' => 'Teknologi & Pemrograman', 'description' => 'Buku bidang rekayasa perangkat lunak, coding, dan teknologi masa depan.'],
            ['name' => 'Sains & Fisika Terapan', 'description' => 'Ilmu pengetahuan alam, fisika kuantum, dan eksperimen biologi.'],
            ['name' => 'Strategi & Kepemimpinan', 'description' => 'Buku taktik taktis, manajemen tim, kepemimpinan, dan komunikasi.'],
            ['name' => 'Fiksi, Fantasi & Sastra', 'description' => 'Koleksi novel sastra imajinatif dan legenda petualangan epik.'],
            ['name' => 'Bisnis & Akuntansi Lembaga', 'description' => 'Panduan akuntansi, keuangan, dan manajemen perkantoran.'],
            ['name' => 'Sejarah & Mitologi Peradaban', 'description' => 'Sejarah kebudayaan kuno dan kisah peradaban dunia.'],
            ['name' => 'Pengembangan Diri & Kebiasaan', 'description' => 'Panduan pola pikir positif, habit juara, dan integritas moral.'],
        ];

        $categoryIds = [];
        foreach ($categoriesData as $cat) {
            $existing = DB::table('categories')->where('name', $cat['name'])->first();
            if ($existing) {
                $categoryIds[$cat['name']] = $existing->id;
            } else {
                $categoryIds[$cat['name']] = DB::table('categories')->insertGetId([
                    'name' => $cat['name'],
                    'slug' => Str::slug($cat['name']),
                    'description' => $cat['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 11. Books & Book Copies
        $booksData = [
            [
                'isbn' => '9786230109911',
                'title' => 'Mastering Algoritma & Struktur Data Tingkat Mahir',
                'synopsis' => 'Panduan komprehensif membedah struktur data graf, tree, dan algoritma optimasi efisien untuk calon pengembang perangkat lunak handal.',
                'category_name' => 'Teknologi & Pemrograman',
                'author_name' => 'Gord Sang Peneliti Arkana',
                'publisher_name' => 'Eruditio Scholar Press',
                'shelf_code' => 'R-A02',
                'publication_year' => 2024,
                'copies_prefix' => 'ALG',
                'total_copies' => 5,
            ],
            [
                'isbn' => '9786230109922',
                'title' => 'Strategi Kepemimpinan & Manajemen Sinergi Tim',
                'synopsis' => 'Prinsip keteguhan disiplin benteng pertahanan dan komunikasi taktis dalam memimpin kelompok mencapai kemenangan bersama.',
                'category_name' => 'Strategi & Kepemimpinan',
                'author_name' => 'Gord Sang Peneliti Arkana',
                'publisher_name' => 'Moniyan Empire Publishing',
                'shelf_code' => 'R-B02',
                'publication_year' => 2023,
                'copies_prefix' => 'LDR',
                'total_copies' => 4,
            ],
            [
                'isbn' => '9786230109933',
                'title' => 'Fisika Kuantum & Dinamika Waktu Relativitas',
                'synopsis' => 'Eksplorasi mendalam mengenai mekanika kuantum, gravitasi ruang-waktu, dan pemodelan matematis energi masa depan.',
                'category_name' => 'Sains & Fisika Terapan',
                'author_name' => 'Natan Timekeeper',
                'publisher_name' => 'Eruditio Scholar Press',
                'shelf_code' => 'R-B01',
                'publication_year' => 2024,
                'copies_prefix' => 'QTM',
                'total_copies' => 4,
            ],
            [
                'isbn' => '9786230109944',
                'title' => 'Kompilasi Sastra & Kisah Legenda Negeri Cadia',
                'synopsis' => 'Antologi cerita rakyat penuh makna budi pekerti tentang harmoni alam, yin dan yang, serta kesetiaan ksatria.',
                'category_name' => 'Fiksi, Fantasi & Sastra',
                'author_name' => 'Kagura Onmyouji',
                'publisher_name' => 'Cadia Riverlands Media',
                'shelf_code' => 'R-A01',
                'publication_year' => 2022,
                'copies_prefix' => 'CDA',
                'total_copies' => 5,
            ],
            [
                'isbn' => '9786230109955',
                'title' => 'Prinsip Dasar Akuntansi Keuangan Lembaga Modern',
                'synopsis' => 'Buku ajar komprehensif mengenai jurnal penyesuaian, laporan neraca lajur, dan otomatisasi pembukuan digital.',
                'category_name' => 'Bisnis & Akuntansi Lembaga',
                'author_name' => 'James Clear',
                'publisher_name' => 'Penerbit Erlangga',
                'shelf_code' => 'R-C01',
                'publication_year' => 2023,
                'copies_prefix' => 'AKL',
                'total_copies' => 4,
            ],
            [
                'isbn' => '9786230109966',
                'title' => 'Panduan Desain Game 3D & Efek Visual Interaktif',
                'synopsis' => 'Teknik merancang animasi partikel, shader visual grafis antariksa, dan mekanika kontrol gameplay yang memukau.',
                'category_name' => 'Teknologi & Pemrograman',
                'author_name' => 'Novaria Astronesia',
                'publisher_name' => 'Eruditio Scholar Press',
                'shelf_code' => 'R-A02',
                'publication_year' => 2024,
                'copies_prefix' => 'GIM',
                'total_copies' => 4,
            ],
            [
                'isbn' => '9786020633176',
                'title' => 'Atomic Habits: Perubahan Kecil Hasil Luar Biasa',
                'synopsis' => 'Metode terbukti membentuk kebiasaan baik dan menghentikan kebiasaan buruk demi performa puncak setiap hari.',
                'category_name' => 'Pengembangan Diri & Kebiasaan',
                'author_name' => 'James Clear',
                'publisher_name' => 'Gramedia Pustaka Utama',
                'shelf_code' => 'R-B02',
                'publication_year' => 2020,
                'copies_prefix' => 'ATM',
                'total_copies' => 5,
            ],
            [
                'isbn' => '9786230109977',
                'title' => 'Ensiklopedi Sejarah Kuno & Mitologi Peradaban',
                'synopsis' => 'Catatan sejarah artefak magis, reruntuhan peradaban kuno, dan kronologi masa lampau yang ditulis dengan sangat rinci.',
                'category_name' => 'Sejarah & Mitologi Peradaban',
                'author_name' => 'Aurora Frost, Ph.D',
                'publisher_name' => 'Moniyan Empire Publishing',
                'shelf_code' => 'R-C02',
                'publication_year' => 2021,
                'copies_prefix' => 'HIS',
                'total_copies' => 3,
            ],
            [
                'isbn' => '9789793062792',
                'title' => 'Laskar Pelangi: Menembus Keterbatasan Meraih Cita',
                'synopsis' => 'Kisah inspiratif perjuangan anak-anak menuntut ilmu dengan dedikasi pantang menyerah dan persahabatan yang tulus.',
                'category_name' => 'Fiksi, Fantasi & Sastra',
                'author_name' => 'Andrea Hirata',
                'publisher_name' => 'Gramedia Pustaka Utama',
                'shelf_code' => 'R-A01',
                'publication_year' => 2018,
                'copies_prefix' => 'LKP',
                'total_copies' => 4,
            ],
            [
                'isbn' => '9786230109988',
                'title' => 'Tata Kelola Administrasi Perkantoran & Otomatisasi Bisnis',
                'synopsis' => 'Standar operasional prosedur pengelolaan arsip elektronik, korespondensi dinas, dan layanan prima perkantoran modern.',
                'category_name' => 'Bisnis & Akuntansi Lembaga',
                'author_name' => 'Novaria Astronesia',
                'publisher_name' => 'Penerbit Erlangga',
                'shelf_code' => 'R-C01',
                'publication_year' => 2023,
                'copies_prefix' => 'ADM',
                'total_copies' => 4,
            ],
        ];

        foreach ($booksData as $b) {
            $catId = $categoryIds[$b['category_name']] ?? array_values($categoryIds)[0];
            $authId = $authorIds[$b['author_name']] ?? array_values($authorIds)[0];
            $pubId = $publisherIds[$b['publisher_name']] ?? array_values($publisherIds)[0];
            $shId = $shelfIds[$b['shelf_code']] ?? array_values($shelfIds)[0];

            $book = DB::table('books')->where('isbn', $b['isbn'])->first();
            if (!$book) {
                $bookId = DB::table('books')->insertGetId([
                    'isbn' => $b['isbn'],
                    'title' => $b['title'],
                    'slug' => Str::slug($b['title']),
                    'synopsis' => $b['synopsis'],
                    'category_id' => $catId,
                    'author_id' => $authId,
                    'publisher_id' => $pubId,
                    'shelf_id' => $shId,
                    'publication_year' => $b['publication_year'],
                    'language' => 'Indonesia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create copies
                for ($c = 1; $c <= $b['total_copies']; $c++) {
                    $barcode = sprintf('TZU-%s-%03d', $b['copies_prefix'], $c);
                    DB::table('book_copies')->insert([
                        'book_id' => $bookId,
                        'barcode' => $barcode,
                        'status' => 'available',
                        'condition' => 'good',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
