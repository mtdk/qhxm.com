<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\WorkOrderController;

// 首页相关路由
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/realtime-status', [HomeController::class, 'realtimeStatus'])->name('realtime-status');

// 认证相关路由
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/check-remembered', [LoginController::class, 'checkRememberedUser'])->name('auth.checkRemembered');
});

// 需要认证的路由组
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // 设备管理路由
    Route::prefix('devices')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('devices.index');
        Route::get('/{type}', [DeviceController::class, 'showByType'])->name('devices.show');
        Route::post('/search', [DeviceController::class, 'searchDevices'])->name('devices.search');
        Route::put('/{id}/status', [DeviceController::class, 'updateStatus'])->name('devices.updateStatus');
    });
    
    // 工单管理路由
    Route::prefix('work-orders')->group(function () {
        Route::get('/', [WorkOrderController::class, 'index'])->name('work-orders.index');
        Route::get('/create', [WorkOrderController::class, 'create'])->name('work-orders.create');
        Route::post('/', [WorkOrderController::class, 'store'])->name('work-orders.store');
        Route::get('/{id}', [WorkOrderController::class, 'show'])->name('work-orders.show');
        Route::get('/{id}/edit', [WorkOrderController::class, 'edit'])->name('work-orders.edit');
        Route::put('/{id}', [WorkOrderController::class, 'update'])->name('work-orders.update');
        Route::delete('/{id}', [WorkOrderController::class, 'destroy'])->name('work-orders.destroy');
        Route::post('/{id}/send', [WorkOrderController::class, 'send'])->name('work-orders.send');
    });
});
