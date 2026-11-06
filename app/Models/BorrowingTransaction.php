<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BorrowingTransaction extends Model
{
    use HasFactory;

    protected $table = 'borrowing_transactions';

    protected $fillable = [
        'student_nisn',
        'student_name',
        'tanggal_pinjam',
        'batas_kembali',
        'status_global',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'batas_kembali' => 'date',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(BorrowingDetail::class, 'transaction_id');
    }
}
