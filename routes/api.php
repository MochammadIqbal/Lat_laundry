<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('login/check', [UserController::class, 'getAuthenticatedUser']);
});
