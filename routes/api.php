<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PriceController;
use App\Http\Controllers\Api\ItemPickUpController;
use App\Http\Controllers\Api\VerifyEmailController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\LocationsController;
use App\Http\Controllers\Api\resetPasswordController;
//use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\ParcelHistoryController;
use League\CommonMark\Block\Element\Document;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// public routes
// Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
// Route::post('reset-password', [NewPasswordController::class, 'reset']);
Route::post('forgot-password', [resetPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [resetPasswordController::class, 'resetPassword']);
Route::post('contact-us', [ContactController::class, 'store']);
Route::get('price-lists', [PriceController::class, 'index']);
Route::get('location-lists', [LocationsController::class, 'index']);
Route::get('price-lists/{state}', [PriceController::class, 'show']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);


Route::group(['middleware' => ['auth:api']], function () {
	Route::post('email/verification-notification', [VerifyEmailController::class, 'sendVerificationEmail']);
	Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])->name('verification.verify');
	Route::post('logout', [AuthController::class, 'logout']);
	Route::post('update-user', [AuthController::class, 'updateUser']);
	Route::get('user-details', [AuthController::class, 'userDetails']);
	Route::post('update-password', [AuthController::class, 'updatePassword']);
	Route::post('logout', [AuthController::class, 'logout']);
	Route::get('item-pickup', [ItemPickUpController::class, 'myparcels']);
	Route::post('item-pickup', [ItemPickUpController::class, 'store']);
	Route::get('item-pickup/{tracking_id}', [ItemPickUpController::class, 'show']);
	Route::get('parcel-history', [ParcelHistoryController::class, 'index']);
	Route::get('parcel-history/{tracking_id}', [ParcelHistoryController::class, 'show']);
});


Route::group(['middleware' => ['auth:api', 'adminPermission']], function () {
	Route::get('users', [AuthController::class, 'index']);
	Route::get('users', [AuthController::class, 'destroy']);
	Route::get('contact-us', [ContactController::class, 'index']);
	Route::delete('contact-us/{id}', [ContactController::class, 'destroy']);
	Route::put('item-pickup/{id}', [ItemPickUpController::class, 'update']);
	Route::delete('item-pickup/{id}', [ItemPickUpController::class, 'destroy']);
	Route::post('price-lists', [PriceController::class, 'store']);
	Route::delete('price-lists/{id}', [PriceController::class, 'destroy']);
	Route::put('price-lists/{id}', [PriceController::class, 'update']);
	Route::delete('parcel-history/{id}', [ParcelHistoryController::class, 'destroy']);
	Route::post('add-staff', [AuthController::class, 'register']);
});


Route::group(['middleware' => ['auth:api','staffPermission']], function () {
	Route::patch('parcel-history/{id}', [ParcelHistoryController::class, 'update']);
	Route::post('parcel-history', [ParcelHistoryController::class, 'store']);
});
