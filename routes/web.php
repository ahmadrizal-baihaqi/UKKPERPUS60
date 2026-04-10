<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // Ini akan jadi Landing Page nanti
});

// Wilayah Khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

// Route Kategori
Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
Route::put('/admin/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
Route::delete('/admin/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
});

// Route Buku
Route::get('/admin/books', [AdminController::class, 'books'])->name('admin.books');
Route::post('/admin/books', [AdminController::class, 'storeBook'])->name('admin.books.store');
Route::put('/admin/books/{id}', [AdminController::class, 'updateBook'])->name('admin.books.update');
Route::delete('/admin/books/{id}', [AdminController::class, 'deleteBook'])->name('admin.books.delete');

// Route Anggota
Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

// Route Riwayat Pinjaman
Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');

// Wilayah Khusus User (Siswa)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::post('/borrow', [UserController::class, 'borrow'])->name('book.borrow');
    Route::post('/return/{id}', [UserController::class, 'returnBook'])->name('book.return');
});

require __DIR__.'/auth.php';
