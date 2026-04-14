<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['nama_kategori'];

    // Relasi: Satu kategori punya banyak buku
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
