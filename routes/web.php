<?php

use App\Http\Controllers\BusinessAccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('locale/{locale}', function ($locale) {
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('locale');


Route::middleware('auth')->group(function () {
    Route::get('/', [AdController::class, 'homepage'])->name('rental-ads.homepage');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/business-accounts-contract', [BusinessAccountController::class, 'index'])->name('business-accounts-contract.index');
    Route::get('/business-accounts-contract/export-contract/{id}', [BusinessAccountController::class, 'exportContract'])->name('business-accounts-contract.export-pdf');
    Route::post('/contracts', [BusinessAccountController::class, 'storeContract'])->name('contracts.store');
    Route::get('/contracts/{id}/approve', [BusinessAccountController::class, 'approveContract'])->name('contracts.approve');
    Route::get('/contracts/{id}/reject', [BusinessAccountController::class, 'rejectContract'])->name('contracts.reject');
    Route::get('/contracts/{id}/download', [BusinessAccountController::class, 'downloadContract'])->name('contracts.download');

    Route::get('/rental-ads', [AdController::class, 'rental_ads'])->name('rental-ads.index');
    Route::get('/rental-ads/create', [AdController::class, 'create'])->name('rental-ads.create');
    Route::post('/rental-ads', [AdController::class, 'store'])->name('rental-ads.store');
    Route::post('/rental-ads/{rentalAd}/toggle-favorite', [AdController::class, 'toggleFavorite'])->name('rental-ads.toggle-favorite');

    Route::post('/ads/upload-csv', [AdController::class, 'uploadCsv'])->name('ads.upload-csv');
    Route::post('/ads/{ad}/bids', [AdController::class, 'placeBid'])->name('ads.place-bid');
    Route::get('/ads/{id}', [AdController::class, 'show'])->name('ads.show');
    Route::get('/ads/{ad}/bids', [AdController::class, 'getBids'])->name('ads.get-bids');

});



require __DIR__.'/auth.php';
