<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = ['user_id', 'book_id', 'tanggal_pinjam', 'batas_kembali', 'tanggal_kembali', 'status'];

    // Relasi ke User dan Buku
    public function user() { return $this->belongsTo(User::class); }
    public function book() { return $this->belongsTo(Book::class); }
}
