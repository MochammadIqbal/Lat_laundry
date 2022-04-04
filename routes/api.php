<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DetilTransaksiController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\DashboardController;






Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'store']);

Route::group(['middleware' => ['jwt.verify:admin, kasir, owner']], function() {
    Route::get('login/check', [UserController::class, 'getAuthenticatedUser']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('report', [TransaksiController::class, 'report']);
    Route::get('dashboard', [DashboardController::class, 'index']);

});

Route::group(['middleware' => ['jwt.verify:admin']], function() {
    
    //OUTLET
    
    Route::post('outlet', [OutletController::class, 'store']);
    Route::get('outlet', [OutletController::class, 'getAll']);
    Route::get('outlet/{id}', [OutletController::class, 'getById']);
    Route::put('outlet/{id}', [OutletController::class, 'update']);
    Route::delete('outlet/{id}', [OutletController::class, 'delete']);
    
    //PAKET
    Route::post('paket', [PaketController::class, 'store']);
    Route::get('paket', [PaketController::class, 'getAll']);
    Route::get('paket/{id}', [PaketController::class, 'getById']);
    Route::put('paket/{id}', [PaketController::class, 'update']);
    Route::delete('paket/{id}', [PaketController::class, 'delete']);
    
    //USER
    Route::post('insert_user', [UserController::class, 'store']);
    Route::get('get_user', [UserController::class, 'getAll']);
    Route::get('get_user_id/{id}', [UserController::class, 'getById']);
    Route::put('update_user/{id}', [UserController::class, 'update']);
    Route::delete('delete_user/{id}', [UserController::class, 'delete']);
});

Route::group(['middleware' => ['jwt.verify:admin, kasir']], function() {

    Route::post('member', [MemberController::class, 'store']);
    Route::get('member', [MemberController::class, 'getAll']);
    Route::get('member/{id_member}', [MemberController::class, 'getById']);
    Route::put('member/{id_member}', [MemberController::class, 'update']);
    Route::delete('member/{id_member}', [MemberController::class, 'delete']);

    //TRANSAKSI
    Route::post('transaksi', [TransaksiController::class, 'store']);
    Route::post('transaksi/status/{id}', [TransaksiController::class, 'changeStatus']);
    Route::post('transaksi/bayar/{id}', [TransaksiController::class, 'bayar']);
    Route::get('transaksi/{id}', [TransaksiController::class, 'getById']);
    Route::get('transaksi', [TransaksiController::class, 'getAll']);
    Route::put('transaksi/{id}', [TransaksiController::class, 'update']);

    //DETAIL TRANSAKSI
    Route::post('transaksi/detil/tambah', [DetilTransaksiController::class, 'store']);
    Route::get('transaksi/detil/{id}', [DetilTransaksiController::class, 'getById']);
    Route::get('transaksi/total/{id}', [DetilTransaksiController::class, 'getTotal']);
});
