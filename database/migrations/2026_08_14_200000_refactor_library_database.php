<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorLibraryDatabase extends Migration
{
    public function up()
    {
        // 1. Drop old tables if they exist to replace them with English naming
        Schema::dropIfExists('riwayat_pinjam');
        Schema::dropIfExists('kategori_buku');
        Schema::dropIfExists('profile');
        Schema::dropIfExists('buku');
        Schema::dropIfExists('kategori');
        
        // 2. Roles Table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, librarian, teacher, student
            $table->string('display_name');
            $table->timestamps();
        });

        // 3. Update Users Table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
        });

        // 4. Academic Years & Classes
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 2026/2027
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., X PPLG 1
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // 5. Students & Teachers Profiles
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nis')->unique();
            $table->string('nisn')->nullable()->unique();
            $table->foreignId('class_id')->nullable()->constrained()->nullOnDelete();
            $table->string('major')->nullable(); // jurusan
            $table->string('phone')->nullable();
            $table->year('enrollment_year')->nullable();
            $table->timestamps();
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nip')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('subject')->nullable(); // mata pelajaran
            $table->string('department')->nullable();
            $table->timestamps();
        });

        // 6. Library Core Entities (Categories, Authors, Publishers, Shelves)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('biography')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('shelves', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., R-A01
            $table->string('name');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 7. Books & Book Copies
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn')->nullable()->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('synopsis')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('authors')->nullOnDelete();
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->nullOnDelete();
            $table->foreignId('shelf_id')->nullable()->constrained('shelves')->nullOnDelete();
            $table->year('publication_year')->nullable();
            $table->string('language')->default('Indonesia');
            $table->string('cover_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('copy_code')->unique(); // e.g., BK-0001
            $table->date('procurement_date')->nullable();
            $table->enum('condition', ['good', 'damaged', 'lost'])->default('good');
            $table->enum('status', ['available', 'borrowed', 'reserved', 'maintenance', 'lost'])->default('available');
            $table->timestamps();
        });

        // 8. Borrowing System
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // The borrower (Student/Teacher)
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('returned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned', 'overdue', 'lost'])->default('pending');
            $table->timestamps();
        });

        Schema::create('borrowing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_copy_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // 9. Reservations & Fines
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['waiting', 'available', 'completed', 'cancelled', 'expired'])->default('waiting');
            $table->timestamps();
        });

        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('reason');
            $table->enum('status', ['unpaid', 'paid', 'waived'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 10. Settings & Logs
        Schema::create('library_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('activity');
            $table->string('module')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Dropping in reverse order
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('library_settings');
        Schema::dropIfExists('fines');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('borrowing_items');
        Schema::dropIfExists('borrowings');
        Schema::dropIfExists('book_copies');
        Schema::dropIfExists('books');
        Schema::dropIfExists('shelves');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('students');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('academic_years');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'status', 'avatar']);
        });

        Schema::dropIfExists('roles');
    }
}
