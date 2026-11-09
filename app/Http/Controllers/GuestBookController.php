<?php

namespace App\Http\Controllers;

use App\Models\GuestVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GuestBookController extends Controller
{
    public function index()
    {
        $visits = GuestVisit::orderByDesc('visited_at')->paginate(15);

        return view('guest-book.index', compact('visits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visitor_type' => ['required', 'in:siswa,guru'],
            'identifier' => ['required', 'string', 'max:50'],
            'nama' => ['nullable', 'string', 'max:255'],
            'kelas' => ['nullable', 'string', 'max:50'],
        ]);

        $nama = trim((string) ($validated['nama'] ?? ''));
        $kelas = trim((string) ($validated['kelas'] ?? ''));

        if ($validated['visitor_type'] === 'siswa') {
            $url = config('services.student_api.url', env('STUDENT_API_URL', 'https://roomroom-nine.vercel.app/data-siswa/api'));
            $token = config('services.student_api.token', env('STUDENT_API_TOKEN'));

            try {
                $http = Http::timeout(15)->acceptJson();
                if ($token) {
                    $http = $http->withToken($token)->withHeaders(['X-API-Key' => $token]);
                }

                $response = $http->get($url, [
                    'nisn' => $validated['identifier'],
                    'limit' => 5,
                ]);

                if ($response->successful()) {
                    $rows = $response->json('data', []);
                    if (is_array($rows) && ! empty($rows)) {
                        $row = $rows[0];
                        $nama = $row['nama_lengkap'] ?? $row['nama'] ?? $row['name'] ?? $nama;
                        $kelas = $row['kelas'] ?? $row['class'] ?? $kelas;
                    }
                }
            } catch (\Throwable $e) {
                // fallback ke input manual
            }

            if ($nama === '') {
                return back()->withErrors(['nama' => 'Data siswa tidak ditemukan. Isi nama manual.'])->withInput();
            }
        } else {
            if ($nama === '') {
                return back()->withErrors(['nama' => 'Nama guru wajib diisi.'])->withInput();
            }
        }

        $visit = GuestVisit::create([
            'visitor_type' => $validated['visitor_type'],
            'identifier' => $validated['identifier'],
            'nama' => $nama,
            'kelas' => $kelas ?: null,
            'visited_at' => now(),
        ]);

        $number = GuestVisit::where('id', '<=', $visit->id)->count();

        return redirect()->route('guest-book.index')
            ->with('success', 'Selamat datang, '.$nama.'! Anda adalah pengunjung ke-'.$number.'.');
    }
}
