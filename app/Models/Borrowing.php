<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved_by',
        'returned_to',
        'borrow_date',
        'due_date',
        'return_date',
        'status'
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function returnReceiver()
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    public function items()
    {
        return $this->hasMany(BorrowingItem::class);
    }

    public function fine()
    {
        return $this->hasOne(Fine::class);
    }
}
