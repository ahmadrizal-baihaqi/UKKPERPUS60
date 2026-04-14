<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\Loan; // Tambahkan ini biar lebih rapi panggil Modelnya
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // Mengambil semua riwayat peminjaman beserta data user dan buku
        $recentLoans = Loan::with(['user', 'book'])
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();

        // Hitung statistik untuk kartu dashboard
        $totalBuku = Book::count();
        $totalAnggota = User::where('role', 'user')->count();
        $totalPinjaman = Loan::where('status', 'dipinjam')->count();

        return view('admin.dashboard', compact('recentLoans', 'totalBuku', 'totalAnggota', 'totalPinjaman'));
    }

    // === FITUR KATEGORI ===
    public function categories()
    {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        Category::find($id)->update($request->all());
        return redirect()->back()->with('success', 'Kategori berhasil diubah!');
    }

    public function deleteCategory($id)
    {
        Category::find($id)->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }

    // === FITUR BUKU ===
    public function books()
    {
        $books = Book::with('category')->get();
        $categories = Category::all();
        return view('admin.books', compact('books', 'categories'));
    }

    public function storeBook(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'stok' => 'required|numeric|min:1', // Mencegah angka minus saat tambah
            'category_id' => 'required',
            'cover' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($data);
        return redirect()->back()->with('success', 'Buku berhasil ditambahkan!');
    }

    public function updateBook(Request $request, $id)
    {
        // === BAGIAN YANG HARUS DIGANTI (DITAMBAH VALIDASI) ===
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'stok' => 'required|numeric|min:1', // Mencegah angka minus saat edit
            'category_id' => 'required',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $book = Book::find($id);
        $data = $request->all();

        if ($request->hasFile('cover')) {
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);
        return redirect()->back()->with('success', 'Buku berhasil diperbarui!');
    }

    public function deleteBook($id)
    {
        $book = Book::find($id);
        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }
        $book->delete();
        return redirect()->back()->with('success', 'Buku berhasil dihapus!');
    }

    // === FITUR ANGGOTA (USER) ===
    public function users()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()->back()->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->back()->with('success', 'Data anggota berhasil diupdate!');
    }

    public function deleteUser($id)
    {
        User::find($id)->delete();
        return redirect()->back()->with('success', 'Anggota berhasil dihapus!');
    }

    // FITUR LAPORAN
    public function reports()
    {
        $loans = Loan::with(['user', 'book'])
                ->orderBy('created_at', 'desc')
                ->get();

        return view('admin.reports', compact('loans'));
    }
}
