<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian
        $search = $request->input('search');

        // Query buku dengan relasi kategori
        $books = Book::with('category')
            ->where('stok', '>', 0)
            ->when($search, function($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                             ->orWhere('penulis', 'like', "%{$search}%");
            })
            ->get();

        $myLoans = Loan::with('book')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('dashboard', compact('books', 'myLoans'));
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
            'tanggal_kembali' => Carbon::now(),
        ]);

        Book::find($loan->book_id)->increment('stok');

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }
}
