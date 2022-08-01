<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PriceController;
use App\Http\Controllers\Api\ItemPickUpController;
use App\Http\Controllers\Api\VerifyEmailController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\LocationsController;
use App\Http\Controllers\Api\NewPasswordController;
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
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::post('email/verification-notification', [VerifyEmailController::class, 'sendVerificationEmail'])->middleware('auth:api');
Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])->name('verification.verify')->middleware('auth:api');

Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [NewPasswordController::class, 'reset']);


Route::group(['middleware' => 'auth:api'], function () {

	Route::post('user-details', [AuthController::class, 'userDetails']);
	Route::post('update-user', [AuthController::class, 'updateUser']);
	Route::post('update-password', [AuthController::class, 'updatePassword']);
	Route::post('logout', [AuthController::class, 'logout']);

	//Route::get('organization_details/{id}', [OrganizationController::class, 'show']);
	Route::get('contact-us', [ContactController::class, 'index']);
	//Route::post('update_organization/{id}', [OrganizationController::class, 'update']);
	Route::delete('contact-us/{id}', [ContactController::class, 'destroy']);

	Route::get('item-pickup', [ItemPickUpController::class, 'myparcels']);
	//Route::put('item-pickup/{id}', [ItemPickUpController::class, 'update']);
	Route::delete('item-pickup/{id}', [ItemPickUpController::class, 'destroy']);

	Route::post('price-lists', [PriceController::class, 'store']);
	Route::delete('price-lists/{id}', [PriceController::class, 'destroy']);
	Route::put('price-lists/{id}', [PriceController::class, 'update']);
	Route::post('item-pickup', [ItemPickUpController::class, 'store']);
});

Route::post('contact-us', [ContactController::class, 'store']);

Route::get('item-pickup/{tracking_id}', [ItemPickUpController::class, 'show']);


Route::get('price-lists', [PriceController::class, 'index']);
Route::get('location-lists', [LocationsController::class, 'index']);
Route::get('price-lists/{state}', [PriceController::class, 'show']);
