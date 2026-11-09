@extends('layouts.viho')

@section('title', 'Buku Tamu')

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header py-2"><h5 class="mb-0">Isi Buku Tamu</h5></div>
            <div class="card-body p-3">
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
                    <div class="mb-2">
                        <label class="col-form-label">NISN / NIP</label>
                        <input type="text" name="identifier" value="{{ old('identifier') }}" class="form-control" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </form>
            </div>
        </div>

        @if (session('visitor_number'))
            <div class="card mt-3 text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Anda Pengunjung Ke</h6>
                    <h1 class="display-1 fw-bold text-primary mb-0">{{ session('visitor_number') }}</h1>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection