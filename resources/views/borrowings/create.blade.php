@extends('layouts.viho')

@section('title', 'Transaksi Peminjaman Baru')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Cari Siswa</h5></div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <input type="text" id="student-name" class="form-control" placeholder="Ketik nama siswa...">
                    <button type="button" id="btn-search" class="btn btn-primary">Cari</button>
                </div>
                <div id="student-results" class="list-group mb-3"></div>
                <div id="selected-student" class="alert alert-info d-none">
                    <div class="fw-bold" id="selected-name"></div>
                    <div class="small">NISN: <span id="selected-nisn"></span> | Kelas: <span id="selected-kelas"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Pilih Buku</h5></div>
            <div class="card-body">
                <div class="list-group mb-3" style="max-height:260px;overflow-y:auto;">
                    @foreach ($books as $book)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-medium">{{ $book->judul }}</div>
                                <small class="text-muted">{{ $book->kode_buku }} | Stok: {{ $book->stok_tersedia }}</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-add-book" data-id="{{ $book->id }}" data-title="{{ $book->judul }}">Tambah</button>
                        </div>
                    @endforeach
                </div>
                <h6>Keranjang Peminjaman</h6>
                <div id="cart" class="list-group mb-2"></div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('borrowings.store') }}" method="POST" class="card mt-3" id="borrowingForm">
    @csrf
    <div class="card-body">
        <input type="hidden" name="student_nisn" id="input-nisn">
        <input type="hidden" name="student_name" id="input-name">
        <div id="input-book-ids"></div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="col-form-label">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="col-form-label">Batas Kembali</label>
                <input type="date" name="batas_kembali" value="{{ date('Y-m-d', strtotime('+7 days')) }}" class="form-control" required>
            </div>
        </div>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmSaveModal">Simpan Transaksi</button>
        <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>

<script>
const selectedStudent = { nisn: '', name: '', kelas: '' };
const cart = [];

function renderSelected() {
    if (selectedStudent.nisn) {
        const el = document.getElementById('selected-student');
        el.classList.remove('d-none');
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
        div.className = 'list-group-item d-flex justify-content-between align-items-center';
        div.innerHTML = `<span>${item.title}</span>
            <button type="button" class="btn btn-sm btn-outline-danger" data-idx="${idx}">Hapus</button>`;
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
    resEl.innerHTML = '<div class="list-group-item text-muted">Mencari...</div>';
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
            resEl.innerHTML = '<div class="list-group-item text-danger">' + (data.message || 'Tidak ditemukan.') + '</div>';
            return;
        }
        data.students.forEach(s => {
            const div = document.createElement('div');
            div.className = 'list-group-item d-flex justify-content-between align-items-center';
            div.innerHTML = `<div>
                <div class="fw-medium">${s.nama}</div>
                <small class="text-muted">NISN: ${s.nisn} | Kelas: ${s.kelas}</small>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary">Pilih</button>`;
            div.querySelector('button').addEventListener('click', () => {
                selectedStudent.nisn = s.nisn;
                selectedStudent.name = s.nama;
                selectedStudent.kelas = s.kelas;
                renderSelected();
            });
            resEl.appendChild(div);
        });
    } catch (e) {
        resEl.innerHTML = '<div class="list-group-item text-danger">Gagal: ' + e.message + '</div>';
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

<div class="modal fade" id="confirmSaveModal" tabindex="-1" role="dialog" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmSaveModalLabel">Konfirmasi Simpan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyimpan transaksi peminjaman ini?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="button" onclick="document.getElementById('borrowingForm').submit();">Simpan</button>
            </div>
        </div>
    </div>
</div>

@endsection
