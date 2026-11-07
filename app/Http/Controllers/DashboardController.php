<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowingDetail;
use App\Models\BorrowingTransaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalStock = (int) Book::sum('stok_tersedia');
        $totalTransactions = BorrowingTransaction::count();
        $activeLoans = BorrowingDetail::where('status_item', 'Dipinjam')->count();
        $returnedItems = BorrowingDetail::where('status_item', 'Dikembalikan')->count();

        // Peminjaman 6 bulan terakhir
        $labels = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');
            $data[] = BorrowingTransaction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        $recentTransactions = BorrowingTransaction::with('details.book')
            ->latest()
            ->limit(5)
            ->get();

        $lowStockBooks = Book::where('stok_tersedia', '<', 5)
            ->orderBy('stok_tersedia')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalBooks',
            'totalStock',
            'totalTransactions',
            'activeLoans',
            'returnedItems',
            'labels',
            'data',
            'recentTransactions',
            'lowStockBooks'
        ));
    }
}
