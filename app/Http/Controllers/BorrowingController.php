<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowingDetail;
use App\Models\BorrowingTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $query = BorrowingTransaction::with(['details.book'])
            ->orderByDesc('tanggal_pinjam')
            ->orderByDesc('created_at');

        if ($q !== '') {
            $query->where(function ($builder) use ($q) {
                $builder->where('student_name', 'like', '%'.$q.'%')
                    ->orWhere('student_nisn', 'like', '%'.$q.'%');
            });
        }

        $transactions = $query->paginate(5)->appends(['q' => $q]);

        return view('borrowings.index', compact('transactions', 'q'));
    }

    public function create()
    {
        $books = Book::where('stok_tersedia', '>', 0)->orderBy('judul')->get();

        return view('borrowings.create', compact('books'));
    }

    public function searchStudents(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:1'],
        ]);

        $needle = mb_strtolower(trim($request->input('name')));
        $queryParam = ctype_digit($needle) ? 'nisn' : 'nama';

        $url = config('services.student_api.url', env('STUDENT_API_URL', 'https://roomroom-nine.vercel.app/data-siswa/api'));
        $token = config('services.student_api.token', env('STUDENT_API_TOKEN'));

        try {
            $http = Http::timeout(15)->acceptJson();
            if ($token) {
                $http = $http->withToken($token)->withHeaders([
                    'X-API-Key' => $token,
                ]);
            }

            $response = $http->get($url, [
                $queryParam => $request->input('name'),
                'limit' => 50,
            ]);

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data siswa (HTTP '.$response->status().').',
                    'students' => [],
                ], 200);
            }

            $json = $response->json();
            $rows = [];
            if (is_array($json)) {
                if (isset($json['data']) && is_array($json['data'])) {
                    $rows = $json['data'];
                } elseif (array_is_list($json)) {
                    $rows = $json;
                }
            }

            $students = [];
            foreach ($rows as $row) {
                $name = $row['nama_lengkap'] ?? $row['nama'] ?? $row['name'] ?? $row['student_name'] ?? '';
                $nisn = $row['nisn'] ?? $row['NISN'] ?? '';
                $kelas = $row['kelas'] ?? $row['class'] ?? $row['Kelas'] ?? '';

                if ($needle !== '' && ! str_contains(mb_strtolower((string) $name), $needle) && ! str_contains((string) $nisn, $needle)) {
                    continue;
                }

                $students[] = [
                    'nama' => $name,
                    'nisn' => $nisn,
                    'kelas' => $kelas,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => count($students).' siswa ditemukan.',
                'students' => $students,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data siswa: '.$e->getMessage(),
                'students' => [],
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_nisn' => ['required', 'string'],
            'student_name' => ['required', 'string'],
            'tanggal_pinjam' => ['required', 'date'],
            'batas_kembali' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'book_ids' => ['required', 'array', 'min:1'],
            'book_ids.*' => ['integer', 'exists:books,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $transaction = BorrowingTransaction::create([
                'student_nisn' => $validated['student_nisn'],
                'student_name' => $validated['student_name'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'batas_kembali' => $validated['batas_kembali'],
                'status_global' => 'Aktif',
            ]);

            foreach (array_unique($validated['book_ids']) as $bookId) {
                $book = Book::lockForUpdate()->findOrFail($bookId);
                if ($book->stok_tersedia < 1) {
                    throw new \RuntimeException('Stok buku "'.$book->judul.'" tidak mencukupi.');
                }

                BorrowingDetail::create([
                    'transaction_id' => $transaction->id,
                    'book_id' => $book->id,
                    'status_item' => 'Dipinjam',
                ]);

                $book->decrement('stok_tersedia');
            }
        });

        return redirect()->route('borrowings.index')->with('success', 'Transaksi peminjaman berhasil disimpan.');
    }

    public function returnItem(BorrowingDetail $detail)
    {
        if ($detail->status_item === 'Dikembalikan') {
            return back()->with('error', 'Item sudah dikembalikan.');
        }

        DB::transaction(function () use ($detail) {
            $detail->update([
                'status_item' => 'Dikembalikan',
                'tanggal_dikembalikan' => now()->toDateString(),
            ]);

            $detail->book()->increment('stok_tersedia');

            $this->refreshTransactionStatus($detail->transaction_id);
        });

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }

    public function returnSelected(Request $request)
    {
        $validated = $request->validate([
            'detail_ids' => ['required', 'array', 'min:1'],
            'detail_ids.*' => ['integer', 'exists:borrowing_details,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $transactionIds = [];
            foreach (array_unique($validated['detail_ids']) as $detailId) {
                $detail = BorrowingDetail::lockForUpdate()->findOrFail($detailId);
                if ($detail->status_item === 'Dikembalikan') {
                    continue;
                }

                $detail->update([
                    'status_item' => 'Dikembalikan',
                    'tanggal_dikembalikan' => now()->toDateString(),
                ]);
                $detail->book()->increment('stok_tersedia');

                $transactionIds[] = $detail->transaction_id;
            }

            foreach (array_unique($transactionIds) as $transactionId) {
                $this->refreshTransactionStatus($transactionId);
            }
        });

        return back()->with('success', 'Buku terpilih berhasil dikembalikan.');
    }

    public function destroy(BorrowingTransaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            foreach ($transaction->details as $detail) {
                if ($detail->status_item !== 'Dikembalikan') {
                    $detail->book()->increment('stok_tersedia');
                }
                $detail->delete();
            }

            $transaction->delete();
        });

        return redirect()->route('borrowings.index')->with('success', 'Transaksi peminjaman berhasil dihapus (dev).');
    }

    public function returnAll(BorrowingTransaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            foreach ($transaction->details()->where('status_item', 'Dipinjam')->get() as $detail) {
                $detail->update([
                    'status_item' => 'Dikembalikan',
                    'tanggal_dikembalikan' => now()->toDateString(),
                ]);
                $detail->book()->increment('stok_tersedia');
            }

            $this->refreshTransactionStatus($transaction->id);
        });

        return back()->with('success', 'Semua buku pada transaksi berhasil dikembalikan.');
    }

    protected function refreshTransactionStatus(int $transactionId): void
    {
        $transaction = BorrowingTransaction::with('details')->findOrFail($transactionId);
        $total = $transaction->details->count();
        $returned = $transaction->details->where('status_item', 'Dikembalikan')->count();

        if ($returned === 0) {
            $status = 'Aktif';
        } elseif ($returned < $total) {
            $status = 'Selesai Sebagian';
        } else {
            $status = 'Selesai Total';
        }

        $transaction->update(['status_global' => $status]);
    }
}
