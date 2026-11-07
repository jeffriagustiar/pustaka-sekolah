@extends('layouts.viho')

@section('title', 'Test Sinkronisasi API')

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="card">
            <div class="card-header"><h5>Test Sinkronisasi API Data Siswa</h5></div>
            <div class="card-body">
                <p class="text-muted">Uji koneksi ke endpoint API data siswa sebelum digunakan untuk transaksi.</p>
                <button id="btn-test" class="btn btn-primary">Test Sinkron</button>
                <div id="result" class="alert mt-3 d-none"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-test').addEventListener('click', async function () {
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Menguji...';
    const resEl = document.getElementById('result');
    resEl.classList.add('d-none');

    try {
        const response = await fetch("{{ route('api-test.test') }}", {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        });
        const data = await response.json();
        resEl.classList.remove('d-none');
        if (data.success) {
            resEl.className = 'alert alert-success mt-3';
            resEl.textContent = data.message;
        } else {
            resEl.className = 'alert alert-danger mt-3';
            resEl.textContent = data.message;
        }
    } catch (e) {
        resEl.classList.remove('d-none');
        resEl.className = 'alert alert-danger mt-3';
        resEl.textContent = 'Terjadi kesalahan: ' + e.message;
    } finally {
        btn.disabled = false;
        btn.textContent = 'Test Sinkron';
    }
});
</script>
@endsection
