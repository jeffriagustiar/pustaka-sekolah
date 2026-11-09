<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('judul')->paginate(15);

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_buku' => ['required', 'string', 'max:50', 'unique:books,kode_buku'],
            'judul' => ['required', 'string', 'max:255'],
            'pengarang' => ['required', 'string', 'max:255'],
            'penerbit' => ['nullable', 'string', 'max:255'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1000', 'max:'.date('Y')],
            'stok_tersedia' => ['required', 'integer', 'min:0'],
        ]);

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'kode_buku' => ['required', 'string', 'max:50', Rule::unique('books', 'kode_buku')->ignore($book->id)],
            'judul' => ['required', 'string', 'max:255'],
            'pengarang' => ['required', 'string', 'max:255'],
            'penerbit' => ['nullable', 'string', 'max:255'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1000', 'max:'.date('Y')],
            'stok_tersedia' => ['required', 'integer', 'min:0'],
        ]);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Data buku berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $rows = $request->input('books', []);

        if (! is_array($rows) || empty($rows)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data untuk diimport.',
            ], 422);
        }

        $created = 0;
        $updated = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $kode = trim((string) ($row['kode_buku'] ?? ''));
            $judul = trim((string) ($row['judul'] ?? ''));
            $pengarang = trim((string) ($row['pengarang'] ?? ''));

            if ($kode === '' || $judul === '' || $pengarang === '') {
                $errors[] = 'Baris '.($index + 1).': kode_buku, judul, pengarang wajib diisi.';

                continue;
            }

            $data = [
                'kode_buku' => $kode,
                'judul' => $judul,
                'pengarang' => $pengarang,
                'penerbit' => trim((string) ($row['penerbit'] ?? '')) ?: null,
                'tahun_terbit' => isset($row['tahun_terbit']) && $row['tahun_terbit'] !== '' ? (int) $row['tahun_terbit'] : null,
                'stok_tersedia' => isset($row['stok_tersedia']) && $row['stok_tersedia'] !== '' ? (int) $row['stok_tersedia'] : 0,
            ];

            $existing = Book::where('kode_buku', $kode)->first();
            if ($existing) {
                $existing->update($data);
                $updated++;
            } else {
                Book::create($data);
                $created++;
            }
        }

        return response()->json([
            'success' => empty($errors),
            'message' => "Import selesai. {$created} ditambahkan, {$updated} diperbarui.".($errors ? ' Ada '.count($errors).' baris bermasalah.' : ''),
            'created' => $created,
            'updated' => $updated,
            'errors' => $errors,
        ], empty($errors) ? 200 : 207);
    }
}
