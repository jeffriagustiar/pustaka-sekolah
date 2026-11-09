@extends('layouts.viho')

@section('title', 'Buku Tamu')

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="card">
            <div class="card-header"><h5>Isi Buku Tamu</h5></div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('guest-book.store') }}" method="POST" class="theme-form">
                    @csrf
                    <div class="mb-3">
                        <label class="col-form-label">Jenis Pengunjung</label>
                        <select name="visitor_type" id="visitor_type" class="form-control" required>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">NISN / NIP</label>
                        <input type="text" name="identifier" value="{{ old('identifier') }}" class="form-control" required>
                    </div>
                    <div class="mb-3" id="field_nama">
                        <label class="col-form-label">Nama (khusus guru, atau jika data siswa tidak ditemukan)</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control">
                    </div>
                    <div class="mb-3" id="field_kelas">
                        <label class="col-form-label">Kelas (khusus siswa, otomatis dari API)</label>
                        <input type="text" name="kelas" value="{{ old('kelas') }}" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Kunjungan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="card">
            <div class="card-header"><h5>Pengunjung Terbaru</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($visits as $visit)
                                <tr>
                                    <td>{{ $visit->id }}</td>
                                    <td>{{ $visit->nama }}</td>
                                    <td>{{ ucfirst($visit->visitor_type) }}</td>
                                    <td>{{ $visit->kelas ?? '-' }}</td>
                                    <td>{{ $visit->visited_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada pengunjung.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">
                    {{ $visits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
