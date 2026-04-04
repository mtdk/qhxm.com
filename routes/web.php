<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\DeviceRepairController;

// 首页相关路由
Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/realtime-status', [HomeController::class, 'realtimeStatus'])->name('realtime-status')->middleware('auth');

// 认证相关路由
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/check-remembered', [LoginController::class, 'checkRememberedUser'])->name('auth.checkRemembered');
});

// 注销路由（需要CSRF保护）
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 需要认证的路由组
Route::middleware('auth')->group(function () {
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
    
    // 用户中心路由
    Route::prefix('user')->group(function () {
        Route::get('/info', [HomeController::class, 'userInfo'])->name('user.info');
        Route::get('/name/change', [HomeController::class, 'showChangeName'])->name('user.name.change');
        Route::post('/name/update', [HomeController::class, 'changeName'])->name('user.name.update');
        Route::get('/password/change', [HomeController::class, 'showChangePassword'])->name('user.password.change');
        Route::post('/password/update', [HomeController::class, 'changePassword'])->name('user.password.update');
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
// 工单管理相关路由
Route::get('/work-order/register', [WorkOrderController::class, 'create'])->name('work.order.register');
Route::post('/work-order/register', [WorkOrderController::class, 'store'])->name('work.order.register.store');

// 工单领单相关
Route::get('/work-order/claim', [WorkOrderController::class, 'claim'])->name('work.order.claim');
Route::post('/work-order/claim/{id}', [WorkOrderController::class, 'claimStore'])->name('work.order.claim.store');

// 工单关机相关
Route::get('/work-order/shutdown', [WorkOrderController::class, 'shutdown'])->name('work.order.shutdown');
Route::post('/work-order/shutdown/{id}', [WorkOrderController::class, 'shutdownStore'])->name('work.order.shutdown.store');

// 设备登记相关路由
Route::get('/device-register/fqpfsb', [DeviceRegisterController::class, 'fqpfsb'])->name('device.register.fqpfsb');
Route::post('/device-register/fqpfsb', [DeviceRegisterController::class, 'fqpfsbStore'])->name('device.register.fqpfsb.store');

Route::get('/device-register/kyjsb', [DeviceRegisterController::class, 'kyjsb'])->name('device.register.kyjsb');
Route::post('/device-register/kyjsb', [DeviceRegisterController::class, 'kyjsbStore'])->name('device.register.kyjsb.store');

Route::get('/device-register/bsjsb', [DeviceRegisterController::class, 'bsjsb'])->name('device.register.bsjsb');
Route::post('/device-register/bsjsb', [DeviceRegisterController::class, 'bsjsbStore'])->name('device.register.bsjsb.store');

Route::get('/device-register/fssb', [DeviceRegisterController::class, 'fssb'])->name('device.register.fssb');
Route::post('/device-register/fssb', [DeviceRegisterController::class, 'fssbStore'])->name('device.register.fssb.store');

Route::get('/device-register/ymsb', [DeviceRegisterController::class, 'ymsb'])->name('device.register.ymsb');
Route::post('/device-register/ymsb', [DeviceRegisterController::class, 'ymsbStore'])->name('device.register.ymsb.store');

// 设备报修相关
Route::get('/device-repair/register', [DeviceRepairController::class, 'create'])->name('device.repair.register');
Route::post('/device-repair/register', [DeviceRepairController::class, 'store'])->name('device.repair.register.store');

Route::get('/device-report/review', [DeviceRepairController::class, 'review'])->name('device.report.review');
Route::post('/device-report/review/{id}', [DeviceRepairController::class, 'reviewStore'])->name('device.report.review.store');

Route::get('/device-report/confirm', [DeviceRepairController::class, 'confirm'])->name('device.report.confirm');
Route::post('/device-report/confirm/{id}', [DeviceRepairController::class, 'confirmStore'])->name('device.report.confirm.store');

// 手动关机相关
Route::get('/manual-shutdown/fssb', [ManualShutdownController::class, 'fssb'])->name('manual.shutdown.fssb');
Route::post('/manual-shutdown/fssb', [ManualShutdownController::class, 'fssbStore'])->name('manual.shutdown.fssb.store');

Route::get('/manual-shutdown/ymsb', [ManualShutdownController::class, 'ymsb'])->name('manual.shutdown.ymsb');
Route::post('/manual-shutdown/ymsb', [ManualShutdownController::class, 'ymsbStore'])->name('manual.shutdown.ymsb.store');

Route::get('/manual-shutdown/fqpfsb', [ManualShutdownController::class, 'fqpfsb'])->name('manual.shutdown.fqpfsb');
Route::post('/manual-shutdown/fqpfsb', [ManualShutdownController::class, 'fqpfsbStore'])->name('manual.shutdown.fqpfsb.store');

Route::get('/manual-shutdown/bsj', [ManualShutdownController::class, 'bsj'])->name('manual.shutdown.bsj');
Route::post('/manual-shutdown/bsj', [ManualShutdownController::class, 'bsjStore'])->name('manual.shutdown.bsj.store');

Route::get('/manual-shutdown/kyj', [ManualShutdownController::class, 'kyj'])->name('manual.shutdown.kyj');
Route::post('/manual-shutdown/kyj', [ManualShutdownController::class, 'kyjStore'])->name('manual.shutdown.kyj.store');

// 记录打印相关
Route::get('/record-print/fssb', [RecordPrintController::class, 'fssb'])->name('record.print.fssb');
Route::get('/record-print/ymsb', [RecordPrintController::class, 'ymsb'])->name('record.print.ymsb');
Route::get('/record-print/kyjsb', [RecordPrintController::class, 'kyjsb'])->name('record.print.kyjsb');
Route::get('/record-print/bsjsb', [RecordPrintController::class, 'bsjsb'])->name('record.print.bsjsb');
Route::get('/record-print/fqsb', [RecordPrintController::class, 'fqsb'])->name('record.print.fqsb');

// 设备维修相关
Route::get('/device-repair/receive', [DeviceRepairController::class, 'receive'])->name('device.repair.receive');
Route::post('/device-repair/receive/{id}', [DeviceRepairController::class, 'receiveStore'])->name('device.repair.receive.store');

Route::get('/device-repair/confirm', [DeviceRepairController::class, 'confirm'])->name('device.repair.confirm');
Route::post('/device-repair/confirm/{id}', [DeviceRepairController::class, 'confirmStore'])->name('device.repair.confirm.store');

Route::get('/device-repair/query', [DeviceRepairController::class, 'query'])->name('device.repair.query');

// 仓储管理相关
Route::get('/warehouse/manage', [WarehouseController::class, 'index'])->name('warehouse.manage');
Route::get('/warehouse/test', [WarehouseController::class, 'test'])->name('warehouse.test');
// 用户注册路由
Route::get('/register', [Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [Auth\RegisterController::class, 'register'])->name('register.post');
