@extends('layouts.viho')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-sm-6 col-xl-4 col-lg-6">
        <div class="card o-hidden">
            <div class="card-body py-2">
                <div class="media static-top-widget">
                    <div class="media-body">
                        <span class="m-0">Total Buku</span>
                        <h5 class="mb-0 counter">{{ $totalBooks }}</h5>
                    </div>
                    <div class="align-self-center text-center"><i data-feather="book" class="font-primary"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 col-lg-6">
        <div class="card o-hidden">
            <div class="card-body py-2">
                <div class="media static-top-widget">
                    <div class="media-body">
                        <span class="m-0">Total Stok</span>
                        <h5 class="mb-0 counter">{{ $totalStock }}</h5>
                    </div>
                    <div class="align-self-center text-center"><i data-feather="layers" class="font-secondary"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 col-lg-6">
        <div class="card o-hidden">
            <div class="card-body py-2">
                <div class="media static-top-widget">
                    <div class="media-body">
                        <span class="m-0">Sedang Dipinjam</span>
                        <h5 class="mb-0 counter">{{ $activeLoans }}</h5>
                    </div>
                    <div class="align-self-center text-center"><i data-feather="repeat" class="font-danger"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Peminjaman 6 Bulan Terakhir</h5>
            </div>
            <div class="card-body">
                <div id="borrowing-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Buku Tamu Bulan Ini</h5>
            </div>
            <div class="card-body">
                <div id="guest-chart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5>Transaksi Terbaru</h5>
                <a href="{{ route('borrowings.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>NISN</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $trx)
                                <tr>
                                    <td>{{ $trx->student_name }}</td>
                                    <td>{{ $trx->student_nisn }}</td>
                                    <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>
                                        @if($trx->status_global === 'Selesai Total')
                                            <span class="badge badge-success">{{ $trx->status_global }}</span>
                                        @elseif($trx->status_global === 'Selesai Sebagian')
                                            <span class="badge badge-warning">{{ $trx->status_global }}</span>
                                        @else
                                            <span class="badge badge-primary">{{ $trx->status_global }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Belum ada transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var options = {
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        series: [{ name: 'Peminjaman', data: @json($data) }],
        xaxis: { categories: @json($labels) },
        colors: ['#24695c'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '45%' } },
        dataLabels: { enabled: false },
    };
    var chart = new ApexCharts(document.querySelector('#borrowing-chart'), options);
    chart.render();

    var guestOptions = {
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        series: [{ name: 'Pengunjung', data: @json($guestData) }],
        xaxis: { categories: @json($guestLabels), title: { text: 'Tanggal' } },
        colors: ['#ba895d'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
        dataLabels: { enabled: false },
    };
    var guestChart = new ApexCharts(document.querySelector('#guest-chart'), guestOptions);
    guestChart.render();
});
</script>
@endpush