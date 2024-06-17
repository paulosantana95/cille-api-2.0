<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\DashboardController;

Route::controller(AuthController::class)->group(function () {
  Route::post('login', 'login');
  Route::post('register', 'register');
  Route::post('recover-password', 'requestPasswordRecovery');
  Route::post('reset-password', 'resetPassword');
});

Route::controller(AuthController::class)->group(function () {
  Route::post('logout', 'logout');
  Route::post('authorization-sign', 'authorizationSign');
  Route::post('get-token', 'getToken');
  Route::post('refresh-token', 'refreshToken');
  Route::post('generate-sign', 'generateSign');
});

Route::controller(UserController::class)->group(function () {
  Route::get('me', 'me');
  Route::post('save-code', 'saveCode');
  Route::post('refresh', 'refresh');

  Route::prefix('users')->group(function () {
    Route::get('/', 'getUsers')->name('user.list');
    Route::get('{id}/avatar', 'getAvatar')->name('user.avatar');
    Route::post('{id}/avatar', 'updateAvatar')->name('user.avatar.update');
    Route::get('{id}', 'getUsers')->name('user.show');
    Route::patch('{id}', 'editUser')->name('user.update');
    Route::delete('{id}', 'deleteUser')->name('user.delete');
  });
})->middleware('auth:sanctum');

Route::controller(OrdersController::class)->group(function () {
  Route::get('orders', 'getOrders');
})->middleware('auth:sanctum');

Route::controller(PaymentsController::class)->group(function () {
  Route::get('payments', 'getPayments');
})->middleware('auth:sanctum');

Route::controller(DashboardController::class)->group(function () {
  Route::get('dashboard', 'getDashboard');
})->middleware('auth:sanctum');
