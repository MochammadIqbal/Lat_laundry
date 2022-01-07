<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaketController;



Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'store']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('login/check', [UserController::class, 'getAuthenticatedUser']);
    Route::post('logout', [UserController::class, 'logout']);
});

Route::group(['middleware' => ['jwt.verify:admin']], function() {
    
    //OUTLET
    Route::get('outlet', [OutletController::class, 'getAll']);
    Route::get('outlet/{id}', [OutletController::class, 'getById']);
    Route::post('outlet', [OutletController::class, 'store']);
    Route::put('outlet/{id}', [OutletController::class, 'update']);
    Route::delete('outlet/{id}', [OutletController::class, 'delete']);
    
    //PAKET
    Route::get('paket', [PaketController::class, 'getAll']);
    Route::get('paket/{id}', [PaketController::class, 'getById']);
    Route::post('paket', [PaketController::class, 'store']);
    Route::put('paket/{id}', [PaketController::class, 'update']);
    Route::delete('paket/{id}', [PaketController::class, 'delete']);
    
    //USER
    Route::post('user/tambah', [UserController::class, 'register']);    
});

Route::group(['middleware' => ['jwt.verify:admin, kasir']], function() {
    Route::post('member', [MemberController::class, 'store']);
    Route::get('member', [MemberController::class, 'getAll']);
    Route::get('member/{id_member}', [MemberController::class, 'getById']);
    Route::put('member/{id_member}', [MemberController::class, 'update']);
    Route::delete('member/{id_member}', [MemberController::class, 'delete']);
});
