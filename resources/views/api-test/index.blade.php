@extends('layouts.app')

@section('title', 'Test Sinkronisasi API')

@section('content')
<h1 class="text-2xl font-bold mb-4">Test Sinkronisasi API Data Siswa</h1>

<div class="bg-white rounded-xl border border-slate-200 p-6 max-w-2xl">
    <p class="text-sm text-slate-600 mb-4">Uji koneksi ke endpoint API data siswa sebelum digunakan untuk transaksi.</p>
    <button id="btn-test" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Test Sinkron</button>
    <div id="result" class="mt-4 hidden rounded-xl px-4 py-3 text-sm"></div>
</div>

<script>
document.getElementById('btn-test').addEventListener('click', async function () {
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Menguji...';
    const resEl = document.getElementById('result');
    resEl.classList.add('hidden');

    try {
        const response = await fetch("{{ route('api-test.test') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        });
        const data = await response.json();
        resEl.classList.remove('hidden');
        if (data.success) {
            resEl.className = 'mt-4 rounded-xl px-4 py-3 text-sm bg-green-50 border border-green-200 text-green-700';
            resEl.textContent = data.message;
        } else {
            resEl.className = 'mt-4 rounded-xl px-4 py-3 text-sm bg-red-50 border border-red-200 text-red-700';
            resEl.textContent = data.message;
        }
    } catch (e) {
        resEl.classList.remove('hidden');
        resEl.className = 'mt-4 rounded-xl px-4 py-3 text-sm bg-red-50 border border-red-200 text-red-700';
        resEl.textContent = 'Terjadi kesalahan: ' + e.message;
    } finally {
        btn.disabled = false;
        btn.textContent = 'Test Sinkron';
    }
});
</script>
@endsection
