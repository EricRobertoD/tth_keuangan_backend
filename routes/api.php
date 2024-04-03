<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Auth::routes(['verify' => true]);

Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('loginAdmin', 'App\Http\Controllers\AuthController@loginAdmin');
Route::post('registerAdmin', 'App\Http\Controllers\AuthController@registerAdmin');
Route::post('register', 'App\Http\Controllers\AuthController@register');


Route::middleware(['auth:sanctum', 'ability:user'])->group(function () {
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
});


Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {

    Route::get('showUser', 'App\Http\Controllers\UserController@index');
    Route::delete('user/{user}', 'App\Http\Controllers\UserController@destroy');

    Route::post('release', 'App\Http\Controllers\TransaksiController@release');
    Route::post('realisasi', 'App\Http\Controllers\TransaksiController@realisasi');

    Route::delete('transaksi/{transaksi}', 'App\Http\Controllers\TransaksiController@destroy');
    Route::post('logoutAdmin', 'App\Http\Controllers\AuthController@logoutAdmin');
});

Route::middleware(['auth:sanctum', 'ability:admin,user'])->group(function () {
    Route::get('showKategori', 'App\Http\Controllers\KategoriController@index');
    Route::get('showDivisi', 'App\Http\Controllers\DivisiController@index');
    
    Route::get('showTransaksiAgregat', 'App\Http\Controllers\TransaksiAgregatController@index');
    Route::get('showTransaksiHistory', 'App\Http\Controllers\TransaksiController@index');
    Route::get('showTotalSum', 'App\Http\Controllers\TransaksiController@getTotalSum');
    Route::get('showTotalPenyerapan', 'App\Http\Controllers\TransaksiController@getTotalPenyerapan');
});
