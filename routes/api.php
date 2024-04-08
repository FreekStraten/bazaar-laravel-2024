<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


////register user thorugh api



Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/get-csrf', function() {
        return csrf_token();
    });

    Route::post('/register', [RegisteredUserController::class, 'store']);


    Route::post('login', [AuthenticatedSessionController::class, 'store']);


    Route::post('/ads', [AdController::class, 'store'])->name('ads.store');

    Route::post('/reviews/{id}', [ReviewController::class, 'store'])->name('user.reviews.store');

//    Route::post('/ads/{id}/bids', [BidController::class, 'placeBid'])->name('ads.place-bid');
//    Route::post('/ads/{ad_id}/bids/{bid_id}/accept', [BidController::class, 'acceptBid'])->name('ads.accept-bid');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/ads/{id}/bids', [BidController::class, 'placeBid'])->name('ads.place-bid');
    Route::post('/ads/{ad_id}/bids/{bid_id}/accept', [BidController::class, 'acceptBid'])->name('ads.accept-bid');
});


