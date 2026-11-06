@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Daftar Peminjaman</h1>
    <a href="{{ route('borrowings.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Transaksi Baru</a>
</div>

<div class="space-y-4">
    @forelse ($transactions as $trx)
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div>
                    <div class="font-semibold">{{ $trx->student_name }}</div>
                    <div class="text-sm text-slate-500">NISN: {{ $trx->student_nisn }} | Pinjam: {{ $trx->tanggal_pinjam->format('d/m/Y') }} | Batas: {{ $trx->batas_kembali->format('d/m/Y') }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs
                        @if($trx->status_global === 'Selesai Total') bg-green-100 text-green-700
                        @elseif($trx->status_global === 'Selesai Sebagian') bg-yellow-100 text-yellow-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ $trx->status_global }}
                    </span>
                    @if ($trx->status_global !== 'Selesai Total')
                        <form action="{{ route('borrowings.return-all', $trx) }}" method="POST" onsubmit="return confirm('Kembalikan semua buku?')">
                            @csrf
                            <button type="submit" class="text-sm px-3 py-1 border border-slate-300 rounded-lg hover:bg-slate-50">Kembalikan Semua</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left">
                        <tr>
                            <th class="px-3 py-2">Buku</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Tgl Dikembalikan</th>
                            <th class="px-3 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trx->details as $detail)
                            <tr class="border-t border-slate-100">
                                <td class="px-3 py-2">{{ $detail->book->judul ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs
                                        @if($detail->status_item === 'Dikembalikan') bg-green-100 text-green-700
                                        @else bg-blue-100 text-blue-700 @endif">
                                        {{ $detail->status_item }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">{{ $detail->tanggal_dikembalikan ? $detail->tanggal_dikembalikan->format('d/m/Y') : '-' }}</td>
                                <td class="px-3 py-2">
                                    @if ($detail->status_item !== 'Dikembalikan')
                                        <form action="{{ route('borrowings.return-item', $detail) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-indigo-600 hover:underline text-sm">Kembalikan</button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-center text-slate-500">Belum ada transaksi peminjaman.</div>
    @endforelse
</div>

<div class="mt-4">
    {{ $transactions->links() }}
</div>
@endsection
