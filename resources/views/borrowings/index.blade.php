@extends('layouts.viho')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Daftar Peminjaman</h5>
                    <a href="{{ route('borrowings.create') }}" class="btn btn-primary">Transaksi Baru</a>
                </div>
                <form method="GET" action="{{ route('borrowings.index') }}" class="mb-2">
                    <div class="input-group">
                        <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Cari nama atau NISN siswa...">
                        <button type="submit" class="btn btn-outline-primary">Cari</button>
                        @if(!empty($q))
                            <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="card-body">
                @forelse ($transactions as $trx)
                    <div class="card mb-3">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $trx->student_name }}</h6>
                                <small class="text-muted">NISN: {{ $trx->student_nisn }} | Pinjam: {{ $trx->tanggal_pinjam->format('d/m/Y') }} | Batas: {{ $trx->batas_kembali->format('d/m/Y') }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($trx->status_global === 'Selesai Total')
                                    <span class="badge badge-success">{{ $trx->status_global }}</span>
                                @elseif($trx->status_global === 'Selesai Sebagian')
                                    <span class="badge badge-warning">{{ $trx->status_global }}</span>
                                @else
                                    <span class="badge badge-primary">{{ $trx->status_global }}</span>
                                @endif
                                @if ($trx->status_global !== 'Selesai Total')
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#confirmActionModal" data-action="{{ route('borrowings.return-all', $trx) }}" data-message="Kembalikan semua buku pada transaksi ini?">Kembalikan Semua</button>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTransactionModal" data-action="{{ route('borrowings.destroy', $trx) }}" data-title="{{ $trx->student_name }}">Hapus (Dev)</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('borrowings.return-selected') }}" method="POST">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th style="width:40px;">Pilih</th>
                                                <th>Buku</th>
                                                <th>Status</th>
                                                <th>Tgl Dikembalikan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trx->details as $detail)
                                                <tr>
                                                    <td>
                                                        @if ($detail->status_item !== 'Dikembalikan')
                                                            <input type="checkbox" name="detail_ids[]" value="{{ $detail->id }}">
                                                        @else
                                                            <input type="checkbox" disabled checked>
                                                        @endif
                                                    </td>
                                                    <td>{{ $detail->book->judul ?? '-' }}</td>
                                                    <td>
                                                        @if($detail->status_item === 'Dikembalikan')
                                                            <span class="badge badge-success">{{ $detail->status_item }}</span>
                                                        @else
                                                            <span class="badge badge-primary">{{ $detail->status_item }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $detail->tanggal_dikembalikan ? $detail->tanggal_dikembalikan->format('d/m/Y') : '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if ($trx->status_global !== 'Selesai Total')
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-sm btn-primary">Kembalikan Terpilih</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Belum ada transaksi peminjaman.</div>
                @endforelse
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Aksi -->
<div class="modal fade" id="confirmActionModal" tabindex="-1" role="dialog" aria-labelledby="confirmActionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmActionModalLabel">Konfirmasi</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmActionMessage">Apakah Anda yakin?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <form id="confirmActionForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">Ya</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Transaksi (Dev) -->
<div class="modal fade" id="deleteTransactionModal" tabindex="-1" role="dialog" aria-labelledby="deleteTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTransactionModalLabel">Konfirmasi Hapus (Dev)</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus transaksi <strong id="deleteTransactionTitle"></strong>? Stok buku yang belum dikembalikan akan dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <form id="deleteTransactionForm" method="POST" class="d-inline">
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
document.getElementById('confirmActionModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var action = button.getAttribute('data-action');
    var message = button.getAttribute('data-message');
    document.getElementById('confirmActionForm').setAttribute('action', action);
    document.getElementById('confirmActionMessage').textContent = message || 'Apakah Anda yakin?';
});

document.getElementById('deleteTransactionModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var action = button.getAttribute('data-action');
    var title = button.getAttribute('data-title');
    document.getElementById('deleteTransactionForm').setAttribute('action', action);
    document.getElementById('deleteTransactionTitle').textContent = title;
});
</script>
@endpush

@push('scripts')
<script>
(function () {
    var input = document.querySelector('input[name="q"]');
    var form = input ? input.closest('form') : null;
    if (!input || !form) return;
    var timer = null;
    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            form.submit();
        }, 400);
    });
})();
</script>
@endpush

@endsection