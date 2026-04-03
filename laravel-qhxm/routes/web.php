<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\DeviceRepairController;

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
    
    // 设备维修管理路由
    Route::prefix('device-repairs')->group(function () {
        Route::get('/', [DeviceRepairController::class, 'index'])->name('device-repairs.index');
        Route::get('/create', [DeviceRepairController::class, 'create'])->name('device-repairs.create');
        Route::post('/', [DeviceRepairController::class, 'store'])->name('device-repairs.store');
        Route::get('/{id}', [DeviceRepairController::class, 'show'])->name('device-repairs.show');
        Route::get('/{id}/edit', [DeviceRepairController::class, 'edit'])->name('device-repairs.edit');
        Route::put('/{id}', [DeviceRepairController::class, 'update'])->name('device-repairs.update');
        Route::delete('/{id}', [DeviceRepairController::class, 'destroy'])->name('device-repairs.destroy');
        Route::post('/{id}/status', [DeviceRepairController::class, 'updateStatus'])->name('device-repairs.updateStatus');
        Route::get('/statistics', [DeviceRepairController::class, 'statistics'])->name('device-repairs.statistics');
        Route::get('/uncompleted', [DeviceRepairController::class, 'uncompleted'])->name('device-repairs.uncompleted');
    });
});

// 健康检查路由（用于监控）
Route::get('/health', function () {
    $checks = [
        'database' => false,
        'redis' => false,
        'cache' => false,
    ];
    
    // 数据库连接检查
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $checks['database'] = true;
    } catch (\Exception $e) {
        // 忽略错误
    }
    
    // Redis连接检查
    try {
        \Illuminate\Support\Facades\Redis::ping();
        $checks['redis'] = true;
    } catch (\Exception $e) {
        // 忽略错误
    }
    
    // 缓存检查
    try {
        \Illuminate\Support\Facades\Cache::put('health_check', 'ok', 5);
        $checks['cache'] = \Illuminate\Support\Facades\Cache::get('health_check') === 'ok';
    } catch (\Exception $e) {
        // 忽略错误
    }
    
    $allOk = array_reduce($checks, fn($carry, $item) => $carry && $item, true);
    
    return response()->json([
        'status' => $allOk ? 'healthy' : 'unhealthy',
        'checks' => $checks,
        'timestamp' => now()->toISOString(),
    ], $allOk ? 200 : 503);
});
