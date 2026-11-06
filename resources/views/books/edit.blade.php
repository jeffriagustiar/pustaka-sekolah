@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Buku</h1>

<form action="{{ route('books.update', $book) }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4 max-w-2xl">
    @csrf
    @method('PUT')
    <div>
        <label class="block text-sm font-medium mb-1">Kode Buku</label>
        <input type="text" name="kode_buku" value="{{ old('kode_buku', $book->kode_buku) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Judul</label>
        <input type="text" name="judul" value="{{ old('judul', $book->judul) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Pengarang</label>
        <input type="text" name="pengarang" value="{{ old('pengarang', $book->pengarang) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Stok Tersedia</label>
        <input type="number" name="stok_tersedia" value="{{ old('stok_tersedia', $book->stok_tersedia) }}" min="0" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan</button>
        <a href="{{ route('books.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50">Batal</a>
    </div>
</form>
@endsection
