<?php

namespace App\Http\Controllers;

use App\Models\DeviceRepairOrder;
use App\Models\UserTb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class DeviceRepairController extends Controller
{
    /**
     * 显示维修申请表单
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // 生成维修单号
        $repairId = $this->generateRepairId();
        $today = date('Y-m-d');
        
        // 获取当前用户信息
        $userName = session('uname', '');
        $departmentId = session('department_id', '');
        
        return view('device-repairs.create', compact('repairId', 'today', 'userName', 'departmentId'));
    }

    /**
     * 保存维修申请
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repair_id' => 'required|string|max:50',
            'device_id' => 'required|string|max:50',
            'option' => 'required|in:FS,YM,KY,BS,FQ',
            'device_name' => 'required|string|max:100',
            'apply_time' => 'required|date',
            'brief_content' => 'nullable|string|max:500',
            'use_name' => 'required|string|max:50',
            'use_department' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // 开始数据库事务
        DB::beginTransaction();

        try {
            // 创建维修订单
            $repairOrder = DeviceRepairOrder::create([
                'repair_id' => $validated['repair_id'],
                'device_id' => $validated['device_id'],
                'device_name' => $validated['device_name'],
                'device_type' => $validated['option'],
                'use_department' => $validated['use_department'],
                'use_name' => $validated['use_name'],
                'brief_content' => $validated['brief_content'] ?? '设备故障',
                'apply_time' => $validated['apply_time'],
                'repair_status' => 0, // 提交维修
                'progress' => 10, // 初始进度10%
                'repair_msg' => '申请已提交，等待主管确认...',
            ]);

            // 更新设备状态为故障
            $this->updateDeviceStatus($validated['option'], $validated['device_id'], 'F');

            DB::commit();

            return redirect()->route('device-repairs.index')
                ->with('success', '维修申请提交成功！维修单号：' . $validated['repair_id']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '提交失败：' . $e->getMessage())->withInput();
        }
    }

    /**
     * 显示维修订单列表
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $repairOrders = DeviceRepairOrder::orderBy('id', 'desc')->paginate(20);
        
        // 统计信息
        $total = DeviceRepairOrder::count();
        $submitted = DeviceRepairOrder::where('repair_status', 0)->count();
        $confirmed = DeviceRepairOrder::where('repair_status', 1)->count();
        $repairing = DeviceRepairOrder::where('repair_status', 2)->count();
        $completed = DeviceRepairOrder::where('repair_status', 3)->count();
        $finished = DeviceRepairOrder::where('repair_status', 4)->count();
        
        $stats = [
            'total' => $total,
            'submitted' => $submitted,
            'confirmed' => $confirmed,
            'repairing' => $repairing,
            'completed' => $completed,
            'finished' => $finished,
        ];

        return view('device-repairs.index', compact('repairOrders', 'stats'));
    }

    /**
     * 显示单个维修订单详情
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $repairOrder = DeviceRepairOrder::findOrFail($id);
        
        return view('device-repairs.show', compact('repairOrder'));
    }

    /**
     * 显示编辑维修订单表单
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $repairOrder = DeviceRepairOrder::findOrFail($id);
        
        return view('device-repairs.edit', compact('repairOrder'));
    }

    /**
     * 更新维修订单
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $repairOrder = DeviceRepairOrder::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'repair_content' => 'nullable|string|max:1000',
            'repairer_name' => 'nullable|string|max:50',
            'repairer_department' => 'nullable|string|max:50',
            'repair_start_time' => 'nullable|date',
            'repair_end_time' => 'nullable|date',
            'progress' => 'nullable|integer|min:0|max:100',
            'repair_msg' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // 根据进度更新状态
        if (isset($validated['progress'])) {
            $progress = $validated['progress'];
            
            if ($progress >= 100) {
                $validated['repair_status'] = 4; // 已完成
                $validated['repair_msg'] = '维修已完成，等待最终确认';
            } elseif ($progress >= 80) {
                $validated['repair_status'] = 3; // 机修完成
                $validated['repair_msg'] = '机修已完成，等待主管确认';
            } elseif ($progress >= 50) {
                $validated['repair_status'] = 2; // 机修确认
                $validated['repair_msg'] = '机修确认，正在维修中';
            } elseif ($progress >= 20) {
                $validated['repair_status'] = 1; // 主管确认
                $validated['repair_msg'] = '主管已确认，等待机修处理';
            }
        }

        $repairOrder->update($validated);

        // 如果维修完成，恢复设备状态
        if (isset($validated['repair_status']) && $validated['repair_status'] == 4) {
            $this->updateDeviceStatus($repairOrder->device_type, $repairOrder->device_id, 'T');
        }

        return redirect()->route('device-repairs.index')
            ->with('success', '维修订单更新成功');
    }

    /**
     * 删除维修订单
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $repairOrder = DeviceRepairOrder::findOrFail($id);
        
        // 恢复设备状态
        $this->updateDeviceStatus($repairOrder->device_type, $repairOrder->device_id, 'T');
        
        $repairOrder->delete();

        return redirect()->route('device-repairs.index')
            ->with('success', '维修订单删除成功');
    }

    /**
     * 更新维修状态（API）
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $repairOrder = DeviceRepairOrder::findOrFail($id);

        $request->validate([
            'status' => 'required|integer|min:0|max:4',
            'progress' => 'required|integer|min:0|max:100',
            'message' => 'nullable|string|max:100',
        ]);

        $repairOrder->update([
            'repair_status' => $request->input('status'),
            'progress' => $request->input('progress'),
            'repair_msg' => $request->input('message', $repairOrder->repair_msg),
        ]);

        // 如果维修完成，恢复设备状态
        if ($request->input('status') == 4) {
            $this->updateDeviceStatus($repairOrder->device_type, $repairOrder->device_id, 'T');
        }

        return response()->json([
            'success' => true,
            'message' => '状态更新成功',
            'repairOrder' => $repairOrder,
        ]);
    }

    /**
     * 生成维修单号
     *
     * @return string
     */
    private function generateRepairId(): string
    {
        $dateString = date('Ymd');
        
        // 获取最后一个维修订单
        $lastOrder = DeviceRepairOrder::orderBy('id', 'desc')->first();
        
        if ($lastOrder) {
            $lastId = $lastOrder->id;
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }

        return 'WX' . $dateString . str_pad($newId, 3, '0', STR_PAD_LEFT);
    }

    /**
     * 更新设备状态
     *
     * @param string $type 设备类型
     * @param string $deviceId 设备ID
     * @param string $status 状态 (T/F)
     * @return bool
     */
    private function updateDeviceStatus(string $type, string $deviceId, string $status): bool
    {
        $tableMap = [
            'FS' => 'fssb',
            'YM' => 'ymsb',
            'KY' => 'kyjsb',
            'BS' => 'bsjsb',
            'FQ' => 'fqsb',
        ];

        if (!isset($tableMap[$type])) {
            return false;
        }

        try {
            DB::table($tableMap[$type])
                ->where('machine_id', $deviceId)
                ->update(['machine_status' => $status]);
                
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 获取维修进度统计
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        $total = DeviceRepairOrder::count();
        $submitted = DeviceRepairOrder::where('repair_status', 0)->count();
        $confirmed = DeviceRepairOrder::where('repair_status', 1)->count();
        $repairing = DeviceRepairOrder::where('repair_status', 2)->count();
        $completed = DeviceRepairOrder::where('repair_status', 3)->count();
        $finished = DeviceRepairOrder::where('repair_status', 4)->count();

        // 计算平均维修时间
        $averageTime = DeviceRepairOrder::where('repair_status', 4)
            ->whereNotNull('repair_end_time')
            ->whereNotNull('apply_time')
            ->selectRaw('AVG(DATEDIFF(repair_end_time, apply_time)) as avg_days')
            ->first();

        return response()->json([
            'total' => $total,
            'submitted' => $submitted,
            'confirmed' => $confirmed,
            'repairing' => $repairing,
            'completed' => $completed,
            'finished' => $finished,
            'average_days' => $averageTime ? round($averageTime->avg_days, 1) : 0,
        ]);
    }

    /**
     * 获取未完成的维修订单
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uncompleted()
    {
        $repairOrders = DeviceRepairOrder::where('repair_status', '<', 4)
            ->orderBy('repair_status')
            ->orderBy('apply_time')
            ->get();

        return response()->json($repairOrders);
    }
}