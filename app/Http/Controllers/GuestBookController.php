<?php

namespace App\Http\Controllers;

use App\Models\GuestVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GuestBookController extends Controller
{
    public function index()
    {
        return view('guest-book.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:50'],
        ]);

        $identifier = trim($validated['identifier']);

        // Deteksi jenis: NIP (18 digit) = guru, selain itu siswa
        $visitorType = (strlen($identifier) >= 15 && ctype_digit($identifier)) ? 'guru' : 'siswa';

        $nama = $visitorType === 'guru' ? 'Guru ('.$identifier.')' : 'Siswa ('.$identifier.')';
        $kelas = null;

        if ($visitorType === 'siswa') {
            $url = config('services.student_api.url', env('STUDENT_API_URL', 'https://roomroom-nine.vercel.app/data-siswa/api'));
            $token = config('services.student_api.token', env('STUDENT_API_TOKEN'));

            try {
                $http = Http::timeout(15)->acceptJson();
                if ($token) {
                    $http = $http->withToken($token)->withHeaders(['X-API-Key' => $token]);
                }

                $response = $http->get($url, [
                    'nisn' => $identifier,
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
                // fallback ke nilai default
            }
        }

        $number = GuestVisit::whereDate('visited_at', now()->toDateString())->count() + 1;

        $visit = GuestVisit::create([
            'visitor_type' => $visitorType,
            'identifier' => $identifier,
            'nama' => $nama,
            'kelas' => $kelas,
            'visited_at' => now(),
        ]);

        return redirect()->route('guest-book.index')
            ->with('success', 'Anda Pengunjung Ke '.$number)
            ->with('visitor_number', $number);
    }

    public function export(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $visits = GuestVisit::whereYear('visited_at', $year)
            ->whereMonth('visited_at', $mon)
            ->orderBy('visited_at')
            ->get();

        if ($request->boolean('download')) {
            $html = view('guest-book.export-word', compact('visits', 'month'))->render();

            return response($html)
                ->header('Content-Type', 'application/msword')
                ->header('Content-Disposition', 'attachment; filename="buku_tamu_'.$month.'.doc"');
        }

        return view('guest-book.export', compact('visits', 'month'));
    }
}
