<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class WorkOrderController extends Controller
{
    /**
     * 显示工单列表
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $workOrders = WorkOrder::orderBy('id', 'desc')->paginate(20);
        
        return view('work-orders.index', compact('workOrders'));
    }

    /**
     * 显示创建工单表单
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $today = date('Ymd');
        $defaultBatchNumber = $today . '001';
        
        return view('work-orders.create', compact('today', 'defaultBatchNumber'));
    }

    /**
     * 保存新工单
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pro_id' => 'required|string|max:20',
            'bath_number' => 'required|string|max:20',
            'bath_number_index' => 'required|string|size:3',
            'remarks' => 'required|string|max:20',
            'technology_target' => 'required|in:FS,YM',
            'lot_number' => 'nullable|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();
        $batchNumber = $validated['bath_number'] . $validated['bath_number_index'];
        $lotNumber = $validated['lot_number'] ?? 1;

        // 创建多个工单（根据批次数量）
        for ($i = 0; $i < $lotNumber; $i++) {
            WorkOrder::create([
                'pro_id' => strtoupper($validated['pro_id']),
                'bath_number' => $batchNumber,
                'remarks' => $validated['remarks'],
                'technology_target' => $validated['technology_target'],
                'work_state' => 0, // 默认未领取
            ]);
        }

        return redirect()->route('work-orders.index')
            ->with('success', "成功创建 {$lotNumber} 个工单");
    }

    /**
     * 显示单个工单详情
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $workOrder = WorkOrder::findOrFail($id);
        
        return view('work-orders.show', compact('workOrder'));
    }

    /**
     * 显示编辑工单表单
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $workOrder = WorkOrder::findOrFail($id);
        
        return view('work-orders.edit', compact('workOrder'));
    }

    /**
     * 更新工单
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $workOrder = WorkOrder::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'pro_id' => 'required|string|max:20',
            'bath_number' => 'required|string|max:20',
            'remarks' => 'required|string|max:20',
            'technology_target' => 'required|in:FS,YM',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        $workOrder->update([
            'pro_id' => strtoupper($validated['pro_id']),
            'bath_number' => $validated['bath_number'],
            'remarks' => $validated['remarks'],
            'technology_target' => $validated['technology_target'],
        ]);

        return redirect()->route('work-orders.index')
            ->with('success', '工单更新成功');
    }

    /**
     * 删除工单
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $workOrder = WorkOrder::findOrFail($id);
        $workOrder->delete();

        return redirect()->route('work-orders.index')
            ->with('success', '工单删除成功');
    }

    /**
     * 发送工单（改变工单状态）
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function send($id)
    {
        $workOrder = WorkOrder::findOrFail($id);

        // 只能发送未领取的工单
        if ($workOrder->work_state !== 0) {
            return response()->json([
                'success' => false,
                'message' => '只能发送未领取的工单',
            ], 400);
        }

        $workOrder->update([
            'work_state' => 1, // 设置为已领取
        ]);

        return response()->json([
            'success' => true,
            'message' => '工单发送成功',
            'workOrder' => $workOrder,
        ]);
    }

    /**
     * 获取未领取的工单（API）
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unclaimed(Request $request)
    {
        $technology = $request->input('technology');
        
        $query = WorkOrder::where('work_state', 0);
        
        if ($technology) {
            $query->where('technology_target', $technology);
        }
        
        $workOrders = $query->get();
        
        return response()->json($workOrders);
    }

    /**
     * 获取工单统计信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        $total = WorkOrder::count();
        $unclaimed = WorkOrder::where('work_state', 0)->count();
        $claimed = WorkOrder::where('work_state', 1)->count();
        $inProgress = WorkOrder::where('work_state', 2)->count();
        $completed = WorkOrder::where('work_state', 3)->count();

        return response()->json([
            'total' => $total,
            'unclaimed' => $unclaimed,
            'claimed' => $claimed,
            'inProgress' => $inProgress,
            'completed' => $completed,
        ]);
    }
}