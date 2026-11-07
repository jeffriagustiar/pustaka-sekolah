<?php

use App\Http\Controllers\ApiTestController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::resource('books', BookController::class);

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
