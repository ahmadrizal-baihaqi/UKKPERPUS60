<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        // 1. Ambil semua buku dengan relasi kategori, termasuk yang stoknya 0
        $books = Book::with('category')
            ->where('stok', '>=', 0) // Stok 0 tetep tampilin biar user tau bukunya ada tapi habis
            ->get();

        // 2. Ambil data kategori buat pilihan filter di dashboard
        $categories = Category::all();

        // 3. Ambil riwayat pinjaman user
        $myLoans = Loan::with('book.category')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        // 4. Kirim ke view dashboard
        return view('dashboard', compact('books', 'categories', 'myLoans'));
    }

    public function borrow(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'durasi' => 'required|numeric|min:1|max:14',
        ]);

        $book = Book::find($request->book_id);

        if ($book->stok <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        Loan::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'tanggal_pinjam' => Carbon::now(),
            'batas_kembali' => Carbon::now()->addDays((int) $request->durasi),
            'status' => 'dipinjam',
        ]);

        $book->decrement('stok');

        return redirect()->back()->with('success', 'Buku berhasil dipinjam!');
    }

    public function returnBook($id)
    {
        $loan = Loan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($loan->status == 'dikembalikan') {
            return redirect()->back()->with('error', 'Buku sudah dikembalikan.');
        }

        $loan->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => Carbon::now('Asia/Jakarta'),
        ]);

        Book::find($loan->book_id)->increment('stok');

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }
}
