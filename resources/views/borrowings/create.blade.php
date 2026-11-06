@extends('layouts.app')

@section('title', 'Transaksi Peminjaman Baru')

@section('content')
<h1 class="text-2xl font-bold mb-4">Transaksi Peminjaman Baru</h1>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Cari Siswa --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="font-semibold mb-3">Cari Siswa</h2>
        <div class="flex gap-2 mb-4">
            <input type="text" id="student-name" placeholder="Ketik nama siswa..." class="flex-1 border border-slate-300 rounded-lg px-3 py-2">
            <button type="button" id="btn-search" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Cari</button>
        </div>
        <div id="student-results" class="space-y-2"></div>
        <div id="selected-student" class="mt-4 hidden rounded-xl bg-indigo-50 border border-indigo-200 p-3">
            <div class="font-medium" id="selected-name"></div>
            <div class="text-sm text-slate-600">NISN: <span id="selected-nisn"></span> | Kelas: <span id="selected-kelas"></span></div>
        </div>
    </div>

    {{-- Pilih Buku --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="font-semibold mb-3">Pilih Buku</h2>
        <div class="max-h-64 overflow-y-auto space-y-2 mb-4">
            @foreach ($books as $book)
                <div class="flex items-center justify-between border border-slate-100 rounded-lg px-3 py-2">
                    <div>
                        <div class="font-medium text-sm">{{ $book->judul }}</div>
                        <div class="text-xs text-slate-500">{{ $book->kode_buku }} | Stok: {{ $book->stok_tersedia }}</div>
                    </div>
                    <button type="button" class="btn-add-book text-sm px-3 py-1 bg-slate-100 rounded-lg hover:bg-slate-200"
                        data-id="{{ $book->id }}" data-title="{{ $book->judul }}">Tambah</button>
                </div>
            @endforeach
        </div>
        <h3 class="font-medium text-sm mb-2">Keranjang Peminjaman</h3>
        <div id="cart" class="space-y-2 min-h-[40px]"></div>
    </div>
</div>

<form action="{{ route('borrowings.store') }}" method="POST" class="mt-6 bg-white rounded-xl border border-slate-200 p-6 space-y-4 max-w-3xl">
    @csrf
    <input type="hidden" name="student_nisn" id="input-nisn">
    <input type="hidden" name="student_name" id="input-name">
    <div id="input-book-ids"></div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Batas Kembali</label>
            <input type="date" name="batas_kembali" value="{{ date('Y-m-d', strtotime('+7 days')) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
        </div>
    </div>

    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan Transaksi</button>
    <a href="{{ route('borrowings.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50">Batal</a>
</form>

<script>
const selectedStudent = { nisn: '', name: '', kelas: '' };
const cart = [];

function renderSelected() {
    if (selectedStudent.nisn) {
        document.getElementById('selected-student').classList.remove('hidden');
        document.getElementById('selected-name').textContent = selectedStudent.name;
        document.getElementById('selected-nisn').textContent = selectedStudent.nisn;
        document.getElementById('selected-kelas').textContent = selectedStudent.kelas;
        document.getElementById('input-nisn').value = selectedStudent.nisn;
        document.getElementById('input-name').value = selectedStudent.name;
    }
}

function renderCart() {
    const cartEl = document.getElementById('cart');
    const idsEl = document.getElementById('input-book-ids');
    cartEl.innerHTML = '';
    idsEl.innerHTML = '';
    cart.forEach((item, idx) => {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between border border-slate-100 rounded-lg px-3 py-2';
        div.innerHTML = `<span class="text-sm">${item.title}</span>
            <button type="button" class="text-red-600 text-sm" data-idx="${idx}">Hapus</button>`;
        cartEl.appendChild(div);

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'book_ids[]';
        hidden.value = item.id;
        idsEl.appendChild(hidden);
    });
    cartEl.querySelectorAll('button[data-idx]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const i = parseInt(e.target.getAttribute('data-idx'));
            cart.splice(i, 1);
            renderCart();
        });
    });
}

document.getElementById('btn-search').addEventListener('click', async function () {
    const name = document.getElementById('student-name').value.trim();
    if (!name) return;
    const resEl = document.getElementById('student-results');
    resEl.innerHTML = '<div class="text-sm text-slate-500">Mencari...</div>';
    try {
        const response = await fetch("{{ route('borrowings.search-students') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ name }),
        });
        const data = await response.json();
        resEl.innerHTML = '';
        if (!data.success || !data.students.length) {
            resEl.innerHTML = '<div class="text-sm text-red-600">' + (data.message || 'Tidak ditemukan.') + '</div>';
            return;
        }
        data.students.forEach(s => {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between border border-slate-100 rounded-lg px-3 py-2';
            div.innerHTML = `<div>
                <div class="font-medium text-sm">${s.nama}</div>
                <div class="text-xs text-slate-500">NISN: ${s.nisn} | Kelas: ${s.kelas}</div>
            </div>
            <button type="button" class="text-indigo-600 text-sm">Pilih</button>`;
            div.querySelector('button').addEventListener('click', () => {
                selectedStudent.nisn = s.nisn;
                selectedStudent.name = s.nama;
                selectedStudent.kelas = s.kelas;
                renderSelected();
            });
            resEl.appendChild(div);
        });
    } catch (e) {
        resEl.innerHTML = '<div class="text-sm text-red-600">Gagal: ' + e.message + '</div>';
    }
});

document.querySelectorAll('.btn-add-book').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const id = e.target.getAttribute('data-id');
        const title = e.target.getAttribute('data-title');
        if (cart.find(c => c.id === id)) return;
        cart.push({ id, title });
        renderCart();
    });
});

renderCart();
</script>
@endsection
