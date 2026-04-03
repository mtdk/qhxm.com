<?php

namespace App\Http\Controllers;

use App\Models\Fssb;
use App\Models\Ymsb;
use App\Models\Kyjsb;
use App\Models\Bsjsb;
use App\Models\Fqsb;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * 设备搜索API - 根据设备类型获取可用设备列表
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDevices(Request $request)
    {
        $request->validate([
            'option' => 'required|in:FS,YM,KY,BS,FQ',
        ]);

        $option = $request->input('option');
        $devices = [];

        try {
            switch ($option) {
                case 'FS':
                    $devices = Fssb::where('machine_status', 'T')
                        ->orderBy('id')
                        ->get(['machine_id', 'machine_name'])
                        ->toArray();
                    break;
                case 'YM':
                    $devices = Ymsb::where('machine_status', 'T')
                        ->orderBy('id')
                        ->get(['machine_id', 'machine_name'])
                        ->toArray();
                    break;
                case 'KY':
                    $devices = Kyjsb::where('machine_status', 'T')
                        ->orderBy('id')
                        ->get(['machine_id', 'machine_name'])
                        ->toArray();
                    break;
                case 'BS':
                    $devices = Bsjsb::where('machine_status', 'T')
                        ->orderBy('id')
                        ->get(['machine_id', 'machine_name'])
                        ->toArray();
                    break;
                case 'FQ':
                    $devices = Fqsb::where('machine_status', 'T')
                        ->orderBy('id')
                        ->get(['machine_id', 'machine_name'])
                        ->toArray();
                    break;
            }

            return response()->json($devices);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 显示设备管理页面
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $deviceTypes = [
            'FS' => '分散设备',
            'YM' => '研磨设备',
            'KY' => '空压机',
            'BS' => '冰水机',
            'FQ' => '废气设备',
        ];

        $deviceStats = [];

        foreach ($deviceTypes as $code => $name) {
            $deviceStats[$code] = [
                'name' => $name,
                'total' => $this->getDeviceCountByType($code),
                'running' => $this->getRunningDeviceCountByType($code),
            ];
        }

        return view('devices.index', compact('deviceTypes', 'deviceStats'));
    }

    /**
     * 显示特定类型设备列表
     *
     * @param string $type
     * @return \Illuminate\View\View
     */
    public function showByType(string $type)
    {
        $deviceTypes = [
            'FS' => '分散设备',
            'YM' => '研磨设备',
            'KY' => '空压机',
            'BS' => '冰水机',
            'FQ' => '废气设备',
        ];

        if (!isset($deviceTypes[$type])) {
            abort(404, '设备类型不存在');
        }

        $devices = $this->getDevicesByType($type);
        $deviceName = $deviceTypes[$type];

        return view('devices.show', compact('devices', 'deviceName', 'type'));
    }

    /**
     * 获取设备数量（根据类型）
     *
     * @param string $type
     * @return int
     */
    private function getDeviceCountByType(string $type): int
    {
        switch ($type) {
            case 'FS':
                return Fssb::count();
            case 'YM':
                return Ymsb::count();
            case 'KY':
                return Kyjsb::count();
            case 'BS':
                return Bsjsb::count();
            case 'FQ':
                return Fqsb::count();
            default:
                return 0;
        }
    }

    /**
     * 获取运行中设备数量（根据类型）
     *
     * @param string $type
     * @return int
     */
    private function getRunningDeviceCountByType(string $type): int
    {
        switch ($type) {
            case 'FS':
                return Fssb::where('machine_status', 'T')->count();
            case 'YM':
                return Ymsb::where('machine_status', 'T')->count();
            case 'KY':
                return Kyjsb::where('machine_status', 'T')->count();
            case 'BS':
                return Bsjsb::where('machine_status', 'T')->count();
            case 'FQ':
                return Fqsb::where('machine_status', 'T')->count();
            default:
                return 0;
        }
    }

    /**
     * 获取设备列表（根据类型）
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getDevicesByType(string $type)
    {
        switch ($type) {
            case 'FS':
                return Fssb::orderBy('id')->get();
            case 'YM':
                return Ymsb::orderBy('id')->get();
            case 'KY':
                return Kyjsb::orderBy('id')->get();
            case 'BS':
                return Bsjsb::orderBy('id')->get();
            case 'FQ':
                return Fqsb::orderBy('id')->get();
            default:
                return collect();
        }
    }

    /**
     * 更新设备状态
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'type' => 'required|in:FS,YM,KY,BS,FQ',
            'status' => 'required|in:T,F',
        ]);

        $type = $request->input('type');
        $status = $request->input('status');

        try {
            $device = $this->findDeviceByType($type, $id);

            if (!$device) {
                return response()->json(['error' => '设备不存在'], 404);
            }

            $device->machine_status = $status;
            $device->save();

            return response()->json([
                'success' => true,
                'message' => '设备状态更新成功',
                'device' => $device,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 根据类型查找设备
     *
     * @param string $type
     * @param int $id
     * @return mixed
     */
    private function findDeviceByType(string $type, int $id)
    {
        switch ($type) {
            case 'FS':
                return Fssb::find($id);
            case 'YM':
                return Ymsb::find($id);
            case 'KY':
                return Kyjsb::find($id);
            case 'BS':
                return Bsjsb::find($id);
            case 'FQ':
                return Fqsb::find($id);
            default:
                return null;
        }
    }
}