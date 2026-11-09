@extends('layouts.viho')

@section('title', 'Daftar Buku')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5>Daftar Buku</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">Tambah Buku</button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importBookModal">Import Excel</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($books as $book)
                                <tr>
                                    <td>{{ $book->kode_buku }}</td>
                                    <td>{{ $book->judul }}</td>
                                    <td>{{ $book->pengarang }}</td>
                                    <td>{{ $book->penerbit ?? '-' }}</td>
                                    <td>{{ $book->tahun_terbit ?? '-' }}</td>
                                    <td><span class="badge badge-primary">{{ $book->stok_tersedia }}</span></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBookModal"
                                            data-id="{{ $book->id }}"
                                            data-kode="{{ $book->kode_buku }}"
                                            data-judul="{{ $book->judul }}"
                                            data-pengarang="{{ $book->pengarang }}"
                                            data-penerbit="{{ $book->penerbit }}"
                                            data-tahun="{{ $book->tahun_terbit }}"
                                            data-stok="{{ $book->stok_tersedia }}">Edit</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBookModal" data-action="{{ route('books.destroy', $book) }}" data-title="{{ $book->judul }}">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data buku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="addBookModal" tabindex="-1" role="dialog" aria-labelledby="addBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBookModalLabel">Tambah Buku</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('books.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="col-form-label">Kode Buku</label>
                        <input type="text" name="kode_buku" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Pengarang</label>
                        <input type="text" name="pengarang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" min="1000" max="{{ date('Y') }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Stok Tersedia</label>
                        <input type="number" name="stok_tersedia" value="0" min="0" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Buku -->
<div class="modal fade" id="editBookModal" tabindex="-1" role="dialog" aria-labelledby="editBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookModalLabel">Edit Buku</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBookForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="col-form-label">Kode Buku</label>
                        <input type="text" name="kode_buku" id="edit_kode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Judul</label>
                        <input type="text" name="judul" id="edit_judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Pengarang</label>
                        <input type="text" name="pengarang" id="edit_pengarang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Penerbit</label>
                        <input type="text" name="penerbit" id="edit_penerbit" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" id="edit_tahun" min="1000" max="{{ date('Y') }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Stok Tersedia</label>
                        <input type="number" name="stok_tersedia" id="edit_stok" min="0" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importBookModal" tabindex="-1" role="dialog" aria-labelledby="importBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importBookModalLabel">Import Buku dari Excel</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Proses parsing dilakukan di browser. Unduh template, isi data, lalu upload file .xlsx/.xls/.csv.</p>
                <button type="button" class="btn btn-outline-primary mb-3" id="btnDownloadTemplate">Unduh Template</button>
                <div class="mb-3">
                    <input type="file" id="importFile" class="form-control" accept=".xlsx,.xls,.csv">
                </div>
                <div id="importPreview" class="table-responsive d-none">
                    <table class="table table-sm" id="importPreviewTable">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="importStatus" class="mt-2"></div>
                <div class="progress mt-2 d-none" id="importProgress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnImportSubmit" disabled>Import</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteBookModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBookModalLabel">Konfirmasi Hapus</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus buku <strong id="deleteBookTitle"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                <form id="deleteBookForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('editBookModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    document.getElementById('editBookForm').setAttribute('action', '/books/' + id);
    document.getElementById('edit_kode').value = button.getAttribute('data-kode') || '';
    document.getElementById('edit_judul').value = button.getAttribute('data-judul') || '';
    document.getElementById('edit_pengarang').value = button.getAttribute('data-pengarang') || '';
    document.getElementById('edit_penerbit').value = button.getAttribute('data-penerbit') || '';
    document.getElementById('edit_tahun').value = button.getAttribute('data-tahun') || '';
    document.getElementById('edit_stok').value = button.getAttribute('data-stok') || '';
});

document.getElementById('deleteBookModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var action = button.getAttribute('data-action');
    var title = button.getAttribute('data-title');
    document.getElementById('deleteBookForm').setAttribute('action', action);
    document.getElementById('deleteBookTitle').textContent = title;
});
</script>
@endpush

@push('scripts')
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
<script>
(function () {
    var parsedRows = [];
    var btnDownload = document.getElementById('btnDownloadTemplate');
    var fileInput = document.getElementById('importFile');
    var preview = document.getElementById('importPreview');
    var previewBody = document.querySelector('#importPreviewTable tbody');
    var statusEl = document.getElementById('importStatus');
    var btnSubmit = document.getElementById('btnImportSubmit');

    if (btnDownload) {
        btnDownload.addEventListener('click', async function () {
            var workbook = new ExcelJS.Workbook();
            var ws = workbook.addWorksheet('Buku');

            var header = ['kode_buku', 'judul', 'pengarang', 'penerbit', 'tahun_terbit', 'stok_tersedia'];
            var example = ['BK001', 'Contoh Buku', 'Pengarang', 'Penerbit', 2024, 10];

            ws.addRow(header);
            ws.addRow(example);

            // Style header row (baris 1)
            ws.getRow(1).eachCell(function (cell) {
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFD9E2F3' } };
                cell.font = { bold: true, color: { argb: 'FF1F3864' } };
                cell.border = { top: {style:'thin'}, left:{style:'thin'}, bottom:{style:'thin'}, right:{style:'thin'} };
            });

            // Style example row (baris 2)
            ws.getRow(2).eachCell(function (cell) {
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFCE4D6' } };
                cell.font = { italic: true, color: { argb: 'FF7F3F00' } };
                cell.border = { top: {style:'thin'}, left:{style:'thin'}, bottom:{style:'thin'}, right:{style:'thin'} };
            });

            ws.columns = [
                { width: 12 }, { width: 24 }, { width: 18 }, { width: 16 }, { width: 12 }, { width: 10 },
            ];

            var buffer = await workbook.xlsx.writeBuffer();
            var blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'template_buku.xlsx';
            a.click();
            URL.revokeObjectURL(url);
        });
    }

    function renderPreview(rows) {
        previewBody.innerHTML = '';
        rows.forEach(function (r) {
            var tr = document.createElement('tr');
            tr.innerHTML = '<td>' + (r.kode_buku || '') + '</td>' +
                '<td>' + (r.judul || '') + '</td>' +
                '<td>' + (r.pengarang || '') + '</td>' +
                '<td>' + (r.penerbit || '') + '</td>' +
                '<td>' + (r.tahun_terbit || '') + '</td>' +
                '<td>' + (r.stok_tersedia || 0) + '</td>';
            previewBody.appendChild(tr);
        });
        preview.classList.remove('d-none');
    }

    if (fileInput) {
        fileInput.addEventListener('change', function (e) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (evt) {
                var data = new Uint8Array(evt.target.result);
                var workbook = XLSX.read(data, { type: 'array' });
                var sheet = workbook.Sheets[workbook.SheetNames[0]];
                var json = XLSX.utils.sheet_to_json(sheet, { defval: '' });
                // Lewati baris contoh (baris ke-2) jika masih ada
                if (json.length && (json[0].kode_buku === 'BK001' || json[0].judul === 'Contoh Buku')) {
                    json.shift();
                }
                parsedRows = json.map(function (r) {
                    return {
                        kode_buku: r.kode_buku || r.Kode_Buku || r['Kode Buku'] || '',
                        judul: r.judul || r.Judul || '',
                        pengarang: r.pengarang || r.Pengarang || '',
                        penerbit: r.penerbit || r.Penerbit || '',
                        tahun_terbit: r.tahun_terbit || r['Tahun Terbit'] || r.Tahun || '',
                        stok_tersedia: r.stok_tersedia || r.Stok || r.stok || 0,
                    };
                });
                renderPreview(parsedRows);
                btnSubmit.disabled = parsedRows.length === 0;
                statusEl.textContent = parsedRows.length + ' baris terbaca.';
                statusEl.className = 'mt-2 text-muted';
            };
            reader.readAsArrayBuffer(file);
        });
    }

    if (btnSubmit) {
        btnSubmit.addEventListener('click', async function () {
            if (!parsedRows.length) return;
            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Mengimport...';
            statusEl.textContent = 'Mengirim data...';
            var progressEl = document.getElementById('importProgress');
            if (progressEl) progressEl.classList.remove('d-none');
            try {
                const response = await fetch("{{ route('books.import') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ books: parsedRows }),
                });
                const data = await response.json();
                statusEl.textContent = data.message || 'Selesai.';
                statusEl.className = data.success ? 'mt-2 text-success' : 'mt-2 text-warning';
                if (data.success) {
                    setTimeout(function () { window.location.reload(); }, 1200);
                }
            } catch (e) {
                statusEl.textContent = 'Gagal: ' + e.message;
                statusEl.className = 'mt-2 text-danger';
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Import';
                var progressEl = document.getElementById('importProgress');
                if (progressEl) progressEl.classList.add('d-none');
            }
        });
    }
})();
</script>
@endpush

@endsection
