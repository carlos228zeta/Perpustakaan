<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'book_copy_id'
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class, 'book_copy_id');
    }
}
