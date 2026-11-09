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
                        <label class="col-form-label">NISN / NIP</label>
                        <input type="text" name="identifier" value="{{ old('identifier') }}" class="form-control" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        @if (session('visit'))
            @php $v = session('visit'); @endphp
            <div class="card">
                <div class="card-header"><h5>Data Pengunjung</h5></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nama:</strong> {{ $v->nama }}</p>
                    <p class="mb-1"><strong>Jenis:</strong> {{ ucfirst($v->visitor_type) }}</p>
                    <p class="mb-1"><strong>NISN/NIP:</strong> {{ $v->identifier }}</p>
                    <p class="mb-1"><strong>Kelas:</strong> {{ $v->kelas ?? '-' }}</p>
                    <p class="mb-0"><strong>Tanggal Kunjungan:</strong> {{ $v->visited_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection