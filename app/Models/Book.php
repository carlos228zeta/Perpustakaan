<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'isbn',
        'title',
        'slug',
        'synopsis',
        'category_id',
        'author_id',
        'publisher_id',
        'shelf_id',
        'publication_year',
        'language',
        'cover_image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

    public function availableCopies()
    {
        return $this->hasMany(BookCopy::class)->where('status', 'available');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getAvailableCountAttribute()
    {
        return $this->copies()->where('status', 'available')->count();
    }

    public function getTotalCopiesAttribute()
    {
        return $this->copies()->count();
    }

    public function getStatusLabelAttribute()
    {
        if ($this->available_count > 0) {
            return 'Tersedia';
        }
        if ($this->total_copies > 0) {
            return 'Sedang Dipinjam';
        }
        return 'Tidak Tersedia';
    }
}
