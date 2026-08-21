<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = [
            [
                'title' => 'Marmut Merah Jambu',
                'author' => 'Raditya Dika',
                'publisher' => 'Bukune',
                'category' => 'Komedi',
                'isbn' => '9786022200001',
                'publication_year' => 2010,
                'synopsis' => 'Kisah cinta masa SMA Raditya Dika yang penuh dengan komedi dan kepolosan.',
                'cover_image' => 'img/buku/220px-Marmut_Merah_Jambu.jpg'
            ],
            [
                'title' => 'Dilan: Dia adalah Dilanku Tahun 1990',
                'author' => 'Pidi Baiq',
                'publisher' => 'Pastel Books',
                'category' => 'Novel',
                'isbn' => '9786027870413',
                'publication_year' => 2014,
                'synopsis' => 'Kisah cinta masa SMA antara Dilan dan Milea di Bandung pada tahun 1990.',
                'cover_image' => 'img/buku/dilan.jpeg'
            ],
            [
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'author' => 'J.K. Rowling',
                'publisher' => 'Bloomsbury',
                'category' => 'Fantasi',
                'isbn' => '9780747532743',
                'publication_year' => 1997,
                'synopsis' => 'Petualangan pertama Harry Potter di sekolah sihir Hogwarts.',
                'cover_image' => 'img/buku/Harry_Potter_and_the_Sorcerer\'s_Stone.jpg'
            ],
            [
                'title' => 'Hujan',
                'author' => 'Tere Liye',
                'publisher' => 'Gramedia Pustaka Utama',
                'category' => 'Fiksi Ilmiah',
                'isbn' => '9786020324784',
                'publication_year' => 2016,
                'synopsis' => 'Tentang persahabatan, cinta, melupakan, dan hujan.',
                'cover_image' => 'img/buku/Hujan.jpg'
            ],
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'category' => 'Fiksi',
                'isbn' => '9789793062792',
                'publication_year' => 2005,
                'synopsis' => 'Kisah inspiratif anak-anak Belitung yang berjuang menuntut ilmu.',
                'cover_image' => 'img/buku/LaskarPelangi.webp'
            ],
            [
                'title' => 'The Lord of the Rings',
                'author' => 'J.R.R. Tolkien',
                'publisher' => 'Allen & Unwin',
                'category' => 'Fantasi',
                'isbn' => '9780618640157',
                'publication_year' => 1954,
                'synopsis' => 'Kisah epik Frodo Baggins dalam misinya menghancurkan One Ring.',
                'cover_image' => 'img/buku/TheLordsOfTheRings.jpg'
            ]
        ];

        foreach ($books as $data) {
            // Get or create category
            $category = DB::table('categories')->where('name', $data['category'])->first();
            if (!$category) {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $data['category'],
                    'slug' => Str::slug($data['category']) . '-' . time(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                $categoryId = $category->id;
            }

            // Get or create author
            $author = DB::table('authors')->where('name', $data['author'])->first();
            if (!$author) {
                $authorId = DB::table('authors')->insertGetId([
                    'name' => $data['author'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                $authorId = $author->id;
            }

            // Get or create publisher
            $publisher = DB::table('publishers')->where('name', $data['publisher'])->first();
            if (!$publisher) {
                $publisherId = DB::table('publishers')->insertGetId([
                    'name' => $data['publisher'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                $publisherId = $publisher->id;
            }

            // Check if book exists
            $existingBook = DB::table('books')->where('title', $data['title'])->first();
            if (!$existingBook) {
                $bookId = DB::table('books')->insertGetId([
                    'title' => $data['title'],
                    'slug' => Str::slug($data['title']) . '-' . time(),
                    'isbn' => $data['isbn'],
                    'category_id' => $categoryId,
                    'author_id' => $authorId,
                    'publisher_id' => $publisherId,
                    'shelf_id' => null,
                    'publication_year' => $data['publication_year'],
                    'synopsis' => $data['synopsis'],
                    'cover_image' => $data['cover_image'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // Create 5 copies for each book
                for ($i = 1; $i <= 5; $i++) {
                    DB::table('book_copies')->insert([
                        'book_id' => $bookId,
                        'copy_code' => 'BK-' . str_pad($bookId, 4, '0', STR_PAD_LEFT) . '-' . $i,
                        'procurement_date' => Carbon::now()->format('Y-m-d'),
                        'condition' => 'good',
                        'status' => 'available',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
    }
}
