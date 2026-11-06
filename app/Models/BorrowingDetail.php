<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'book_id',
        'tanggal_dikembalikan',
        'status_item',
    ];

    protected $casts = [
        'tanggal_dikembalikan' => 'date',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(BorrowingTransaction::class, 'transaction_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
