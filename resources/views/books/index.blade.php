@extends('layouts.viho')

@section('title', 'Daftar Buku')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5>Daftar Buku</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">Tambah Buku</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($books as $book)
                                <tr>
                                    <td>{{ $book->kode_buku }}</td>
                                    <td>{{ $book->judul }}</td>
                                    <td>{{ $book->pengarang }}</td>
                                    <td>{{ $book->penerbit ?? '-' }}</td>
                                    <td>{{ $book->tahun_terbit ?? '-' }}</td>
                                    <td><span class="badge badge-primary">{{ $book->stok_tersedia }}</span></td>
                                    <td>
                                        <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBookModal" data-action="{{ route('books.destroy', $book) }}" data-title="{{ $book->judul }}">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data buku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="addBookModal" tabindex="-1" role="dialog" aria-labelledby="addBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBookModalLabel">Tambah Buku</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('books.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="col-form-label">Kode Buku</label>
                        <input type="text" name="kode_buku" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Pengarang</label>
                        <input type="text" name="pengarang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" min="1000" max="{{ date('Y') }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Stok Tersedia</label>
                        <input type="number" name="stok_tersedia" value="0" min="0" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteBookModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBookModalLabel">Konfirmasi Hapus</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus buku <strong id="deleteBookTitle"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <form id="deleteBookForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('deleteBookModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var action = button.getAttribute('data-action');
    var title = button.getAttribute('data-title');
    document.getElementById('deleteBookForm').setAttribute('action', action);
    document.getElementById('deleteBookTitle').textContent = title;
});
</script>
@endpush

@endsection
