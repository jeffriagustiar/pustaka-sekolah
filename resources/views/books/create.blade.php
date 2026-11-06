@extends('layouts.viho')

@section('title', 'Tambah Buku')

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="card">
            <div class="card-header"><h5>Tambah Buku</h5></div>
            <div class="card-body">
                <form action="{{ route('books.store') }}" method="POST" class="theme-form">
                    @csrf
                    <div class="mb-3">
                        <label class="col-form-label">Kode Buku</label>
                        <input type="text" name="kode_buku" value="{{ old('kode_buku') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Judul</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Pengarang</label>
                        <input type="text" name="pengarang" value="{{ old('pengarang') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Penerbit</label>
                        <input type="text" name="penerbit" value="{{ old('penerbit') }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}" min="1000" max="{{ date('Y') }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Stok Tersedia</label>
                        <input type="number" name="stok_tersedia" value="{{ old('stok_tersedia', 0) }}" min="0" class="form-control" required>
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
