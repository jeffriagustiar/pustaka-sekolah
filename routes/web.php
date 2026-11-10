<?php

use App\Http\Controllers\ApiTestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestBookController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Publik
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('guest-book', [GuestBookController::class, 'index'])->name('guest-book.index');
Route::post('guest-book', [GuestBookController::class, 'store'])->name('guest-book.store');

// Terproteksi
Route::middleware('auth')->group(function () {
    Route::resource('books', BookController::class);
    Route::post('books/import', [BookController::class, 'import'])->name('books.import');

    Route::get('borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::get('borrowings/search-students', [BorrowingController::class, 'searchStudents'])->name('borrowings.search-students');
    Route::post('borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::post('borrowings/details/{detail}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return-item');
    Route::post('borrowings/return-selected', [BorrowingController::class, 'returnSelected'])->name('borrowings.return-selected');
    Route::post('borrowings/{transaction}/return-all', [BorrowingController::class, 'returnAll'])->name('borrowings.return-all');
    Route::delete('borrowings/{transaction}', [BorrowingController::class, 'destroy'])->name('borrowings.destroy');

    Route::get('api-test', [ApiTestController::class, 'index'])->name('api-test.index');
    Route::get('api-test/test', [ApiTestController::class, 'test'])->name('api-test.test');

    Route::get('guest-book/export', [GuestBookController::class, 'export'])->name('guest-book.export');

    Route::resource('users', UserController::class);
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::put('profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
});
