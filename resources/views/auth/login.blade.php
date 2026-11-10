@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="card">
    <div class="card-header text-center"><h5>Login</h5></div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="col-form-label">Username / Email</label>
                <input type="text" name="login" value="{{ old('login') }}" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="col-form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>
    </div>
</div>
@endsection