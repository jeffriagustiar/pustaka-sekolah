@extends('layouts.viho')

@section('title', 'Cetak Buku Tamu')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Cetak Buku Tamu - {{ $month }}</h5>
        <form method="GET" action="{{ route('guest-book.export') }}" class="d-flex gap-2 no-print">
            <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm">
            <button type="submit" class="btn btn-sm btn-outline-primary">Cari</button>
            <a href="{{ route('guest-book.export', ['month' => $month, 'download' => 1]) }}" class="btn btn-sm btn-primary">Download Word</a>
        </form>
    </div>
    <div class="card-body">
        <div id="printArea" class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>NISN/NIP</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visits as $i => $visit)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $visit->visited_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $visit->nama }}</td>
                            <td>{{ ucfirst($visit->visitor_type) }}</td>
                            <td>{{ $visit->identifier }}</td>
                            <td>{{ $visit->kelas ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea { position: absolute; left: 0; top: 0; width: 100%; }
}
</style>
@endsection