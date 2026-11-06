@extends('layouts.viho')

@section('title', 'Edit Buku')

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="card">
            <div class="card-header"><h5>Edit Buku</h5></div>
            <div class="card-body">
                <form action="{{ route('books.update', $book) }}" method="POST" class="theme-form">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="col-form-label">Kode Buku</label>
                        <input type="text" name="kode_buku" value="{{ old('kode_buku', $book->kode_buku) }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Judul</label>
                        <input type="text" name="judul" value="{{ old('judul', $book->judul) }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Pengarang</label>
                        <input type="text" name="pengarang" value="{{ old('pengarang', $book->pengarang) }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Stok Tersedia</label>
                        <input type="number" name="stok_tersedia" value="{{ old('stok_tersedia', $book->stok_tersedia) }}" min="0" class="form-control" required>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
