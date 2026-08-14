<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'copy_code',
        'procurement_date',
        'condition',
        'status'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function borrowingItems()
    {
        return $this->hasMany(BorrowingItem::class);
    }
}
