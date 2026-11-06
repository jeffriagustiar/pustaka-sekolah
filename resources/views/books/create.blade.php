@extends('layouts.viho')

@section('title', 'Tambah Buku')

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="card">
            <div class="card-header"><h5>Tambah Buku</h5></div>
            <div class="card-body">
                <form action="{{ route('books.store') }}" method="POST" class="theme-form" id="bookForm">
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmSaveModal">Simpan</button>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmSaveModal" tabindex="-1" role="dialog" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmSaveModalLabel">Konfirmasi Simpan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyimpan data buku ini?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="button" onclick="document.getElementById('bookForm').submit();">Simpan</button>
            </div>
        </div>
    </div>
</div>

@endsection
