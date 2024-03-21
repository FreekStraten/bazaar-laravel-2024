<?php

use App\Http\Controllers\BusinessAccountController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalAdController;
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
    Log::info('Current locale before setting: ' . App::getLocale());
    App::setLocale($locale);
    session()->put('locale', $locale);
    Log::info('Current locale after setting: ' . App::getLocale());
    return redirect()->back();
})->name('locale');

Route::get('/', function () {
    return view('/profile');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/business-accounts', [BusinessAccountController::class, 'index'])->name('business-accounts.index');
    Route::get('/business-accounts/export-contract/{id}', [BusinessAccountController::class, 'exportContract'])->name('business-accounts.export-pdf');
    Route::post('/contracts', [BusinessAccountController::class, 'storeContract'])->name('contracts.store');
    Route::get('/contracts/{id}/approve', [BusinessAccountController::class, 'approveContract'])->name('contracts.approve');
    Route::get('/contracts/{id}/reject', [BusinessAccountController::class, 'rejectContract'])->name('contracts.reject');
    Route::get('/contracts/{id}/download', [BusinessAccountController::class, 'downloadContract'])->name('contracts.download');

    Route::get('/rental-ads', [RentalAdController::class, 'index'])->name('rental-ads.index');
    Route::get('/rental-ads/create', [RentalAdController::class, 'create'])->name('rental-ads.create');
    Route::post('/rental-ads', [RentalAdController::class, 'store'])->name('rental-ads.store');
    Route::post('/rental-ads/{rentalAd}/toggle-favorite', [RentalAdController::class, 'toggleFavorite'])->name('rental-ads.toggle-favorite');

});

require __DIR__.'/auth.php';
