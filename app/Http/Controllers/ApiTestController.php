<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiTestController extends Controller
{
    public function index()
    {
        return view('api-test.index');
    }

    public function test(Request $request)
    {
        $url = config('services.student_api.url', env('STUDENT_API_URL', 'https://roomroom-nine.vercel.app/data-siswa/api'));
        $token = config('services.student_api.token', env('STUDENT_API_TOKEN'));

        try {
            $requestHttp = Http::timeout(15)->acceptJson();
            if ($token) {
                $requestHttp = $requestHttp->withToken($token);
            }

            $response = $requestHttp->get($url);

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sinkronisasi gagal. HTTP status: '.$response->status(),
                    'status' => $response->status(),
                ], 200);
            }

            $json = $response->json();
            if (! is_array($json) || ! array_key_exists('data', $json)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Respons tidak memiliki node data yang dapat dibaca.',
                    'status' => $response->status(),
                ], 200);
            }

            $count = is_array($json['data']) ? count($json['data']) : 0;

            return response()->json([
                'success' => true,
                'message' => 'Sinkronisasi Berhasil. '.$count.' data siswa terbaca.',
                'status' => $response->status(),
                'count' => $count,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sinkronisasi gagal: '.$e->getMessage(),
                'status' => 0,
            ], 200);
        }
    }
}
