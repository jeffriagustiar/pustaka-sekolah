@extends('layouts.viho')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5>Daftar Peminjaman</h5>
                <a href="{{ route('borrowings.create') }}" class="btn btn-primary">Transaksi Baru</a>
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
                                    <form action="{{ route('borrowings.return-all', $trx) }}" method="POST" class="d-inline" onsubmit="return confirm('Kembalikan semua buku?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Kembalikan Semua</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Buku</th>
                                            <th>Status</th>
                                            <th>Tgl Dikembalikan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trx->details as $detail)
                                            <tr>
                                                <td>{{ $detail->book->judul ?? '-' }}</td>
                                                <td>
                                                    @if($detail->status_item === 'Dikembalikan')
                                                        <span class="badge badge-success">{{ $detail->status_item }}</span>
                                                    @else
                                                        <span class="badge badge-primary">{{ $detail->status_item }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $detail->tanggal_dikembalikan ? $detail->tanggal_dikembalikan->format('d/m/Y') : '-' }}</td>
                                                <td>
                                                    @if ($detail->status_item !== 'Dikembalikan')
                                                        <form action="{{ route('borrowings.return-item', $detail) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-primary">Kembalikan</button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
@endsection
