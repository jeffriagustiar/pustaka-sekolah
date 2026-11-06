@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Daftar Buku</h1>
    <a href="{{ route('books.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Tambah Buku</a>
</div>

<div class="overflow-x-auto bg-white rounded-xl border border-slate-200">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-100 text-left">
            <tr>
                <th class="px-4 py-3">Kode</th>
                <th class="px-4 py-3">Judul</th>
                <th class="px-4 py-3">Pengarang</th>
                <th class="px-4 py-3">Stok</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">{{ $book->kode_buku }}</td>
                    <td class="px-4 py-3">{{ $book->judul }}</td>
                    <td class="px-4 py-3">{{ $book->pengarang }}</td>
                    <td class="px-4 py-3">{{ $book->stok_tersedia }}</td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="{{ route('books.edit', $book) }}" class="text-indigo-600 hover:underline">Edit</a>
                        <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada data buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $books->links() }}
</div>
@endsection
