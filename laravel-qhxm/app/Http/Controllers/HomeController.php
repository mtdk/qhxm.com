<?php

namespace App\Http\Controllers;

use App\Models\DeviceRepairOrder;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * 系统首页
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 获取各种设备正在运行的数量（从视图表查询）
        $deviceCounts = [
            '废气设备' => $this->getRunningDeviceCount('fqpfsbjl_show'),
            '分散设备' => $this->getRunningDeviceCount('fssbjl_show'),
            '研磨设备' => $this->getRunningDeviceCount('ymsbjl_show'),
            '冰水设备' => $this->getRunningDeviceCount('bsjjl_show'),
            '空压机' => $this->getRunningDeviceCount('kyjsb_show'),
        ];

        // 获取未领取的工单数量
        $workOrderCounts = [
            '分散工单' => WorkOrder::where('work_state', 0)
                ->where('technology_target', 'FS')
                ->count(),
            '研磨工单' => WorkOrder::where('work_state', 0)
                ->where('technology_target', 'YM')
                ->count(),
        ];

        // 获取未完成的维修订单
        $repairOrders = DeviceRepairOrder::where('repair_status', '<>', 4)
            ->orderBy('id', 'desc')
            ->get();

        return view('home', compact('deviceCounts', 'workOrderCounts', 'repairOrders'));
    }

    /**
     * 获取指定视图表中正在运行的设备数量
     *
     * @param string $viewName
     * @return int
     */
    private function getRunningDeviceCount(string $viewName): int
    {
        try {
            $result = DB::table($viewName)
                ->where('machine_status', '开机')
                ->count();
            return (int) $result;
        } catch (\Exception $e) {
            // 如果视图不存在，返回0
            return 0;
        }
    }

    /**
     * 仪表板统计信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard()
    {
        $stats = [
            'total_devices' => [
                'label' => '总设备数',
                'value' => $this->getTotalDevicesCount(),
                'icon' => 'bi-hdd-stack',
                'color' => 'primary',
            ],
            'running_devices' => [
                'label' => '运行中设备',
                'value' => $this->getTotalRunningDevices(),
                'icon' => 'bi-play-circle',
                'color' => 'success',
            ],
            'unclaimed_orders' => [
                'label' => '未领取工单',
                'value' => WorkOrder::where('work_state', 0)->count(),
                'icon' => 'bi-clipboard',
                'color' => 'warning',
            ],
            'active_repairs' => [
                'label' => '进行中维修',
                'value' => DeviceRepairOrder::where('repair_status', '<>', 4)->count(),
                'icon' => 'bi-tools',
                'color' => 'danger',
            ],
        ];

        return response()->json($stats);
    }

    /**
     * 获取总设备数
     *
     * @return int
     */
    private function getTotalDevicesCount(): int
    {
        $tables = ['fssb', 'ymsb', 'kyjsb', 'bsjsb', 'fqsb'];
        $total = 0;
        
        foreach ($tables as $table) {
            try {
                $total += DB::table($table)->count();
            } catch (\Exception $e) {
                // 忽略不存在的表
                continue;
            }
        }
        
        return $total;
    }

    /**
     * 获取运行中设备总数
     *
     * @return int
     */
    private function getTotalRunningDevices(): int
    {
        $views = ['fqpfsbjl_show', 'fssbjl_show', 'ymsbjl_show', 'bsjjl_show', 'kyjsb_show'];
        $total = 0;
        
        foreach ($views as $view) {
            try {
                $total += DB::table($view)
                    ->where('machine_status', '开机')
                    ->count();
            } catch (\Exception $e) {
                // 忽略不存在的视图
                continue;
            }
        }
        
        return $total;
    }

    /**
     * 获取实时设备状态
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function realtimeStatus()
    {
        $deviceTypes = [
            'FS' => '分散机',
            'YM' => '研磨机',
            'KY' => '空压机',
            'BS' => '冰水机',
            'FQ' => '废气设备',
        ];
        
        $status = [];
        
        foreach ($deviceTypes as $code => $name) {
            $status[] = [
                'type' => $name,
                'code' => $code,
                'running' => $this->getRunningCountByType($code),
                'total' => $this->getTotalCountByType($code),
            ];
        }
        
        return response()->json($status);
    }

    /**
     * 获取指定类型的运行设备数量
     *
     * @param string $typeCode
     * @return int
     */
    private function getRunningCountByType(string $typeCode): int
    {
        $viewMap = [
            'FS' => 'fssbjl_show',
            'YM' => 'ymsbjl_show',
            'KY' => 'kyjsb_show',
            'BS' => 'bsjjl_show',
            'FQ' => 'fqpfsbjl_show',
        ];
        
        if (!isset($viewMap[$typeCode])) {
            return 0;
        }
        
        try {
            return DB::table($viewMap[$typeCode])
                ->where('machine_status', '开机')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 获取指定类型的设备总数
     *
     * @param string $typeCode
     * @return int
     */
    private function getTotalCountByType(string $typeCode): int
    {
        $tableMap = [
            'FS' => 'fssb',
            'YM' => 'ymsb',
            'KY' => 'kyjsb',
            'BS' => 'bsjsb',
            'FQ' => 'fqsb',
        ];
        
        if (!isset($tableMap[$typeCode])) {
            return 0;
        }
        
        try {
            return DB::table($tableMap[$typeCode])->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}