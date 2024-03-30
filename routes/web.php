<?php

use App\Http\Controllers\BidController;
use App\Http\Controllers\BusinessAccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
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
    Route::get('/', [HomeController::class, 'homepage'])->name('ads.homepage');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/business-accounts-contract', [BusinessAccountController::class, 'index'])->name('business-accounts-contract.index');
    Route::get('/business-accounts-contract/export-contract/{id}', [BusinessAccountController::class, 'exportContract'])->name('business-accounts-contract.export-pdf');
    Route::post('/contracts', [BusinessAccountController::class, 'storeContract'])->name('contracts.store');
    Route::get('/contracts/{id}/approve', [BusinessAccountController::class, 'approveContract'])->name('contracts.approve');
    Route::get('/contracts/{id}/reject', [BusinessAccountController::class, 'rejectContract'])->name('contracts.reject');
    Route::get('/contracts/{id}/download', [BusinessAccountController::class, 'downloadContract'])->name('contracts.download');

    Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
    Route::get('/ads/{ad}', [AdController::class, 'show'])->name('ads.show');
    Route::get('/ads/create', [AdController::class, 'create'])->name('ads.create');
    Route::post('/ads', [AdController::class, 'store'])->name('ads.store');
    Route::post('/ads/{ad}/toggle-favorite', [AdController::class, 'toggleFavorite'])->name('ads.toggle-favorite');
    Route::post('/ads/upload-csv', [AdController::class, 'uploadCsv'])->name('ads.upload-csv');
    Route::get('/ads/{ad}/qr-code', [AdController::class, 'showQrCode'])->name('ads.show-qr-code');
    Route::get('/user-rented-ads', [AdController::class, 'getUserRentedAds'])->name('ads.user-rented-ads');
    Route::post('/ads/{ad}/set-dates', [AdController::class, 'setDates'])->name('ads.set-dates');

    Route::post('/ads/{ad}/bids', [BidController::class, 'placeBid'])->name('ads.place-bid');
    Route::post('/ads/{ad}/bids/{bid}/accept', [BidController::class, 'acceptBid'])->name('ads.accept-bid');

    Route::get('/reviews/{id}', [ReviewController::class, 'show'])->name('user.reviews.show');
    Route::post('/reviews/{user}', [ReviewController::class, 'store'])->name('user.reviews.store');
    Route::post('/ads/{ad}/reviews', [AdController::class, 'storeReview'])->name('ads.reviews.store');


});



require __DIR__.'/auth.php';
