<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AddressController;

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

Route::post("company/store", [CompanyController::class, "store"])->name("api.company.store");
Route::put('company/{id}', [CompanyController::class, 'update'])->name('api.company.update');
Route::get("company/{id}", [CompanyController::class, "show"])->name("api.company.show");
Route::delete("company/{id}", [CompanyController::class, "destroy"])->name("api.company.destroy");

Route::post("address/store", [AddressController::class, "store"])->name("api.address.store");
Route::put('address/{id}', [AddressController::class, 'update'])->name('api.address.update');
Route::get("address/{id}", [AddressController::class, "show"])->name("api.address.show");
Route::delete("address/{id}", [AddressController::class, "destroy"])->name("api.address.destroy");

Route::post('company/store_same_time', [CompanyController::class, 'storeSameTime'])->name('api.store.same.time');
