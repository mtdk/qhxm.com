<?php

namespace App\Models;

/**
 * 研磨机运行记录模型
 */
class YmsbRecord extends DeviceRecord
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'ymsbrecords';

    /**
     * 可批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'machine_id',
        'register_date',
        'register_time',
        'shutdown_time',
        'total_duration',
        'pro_id',
        'bath_number',
        'machine_status',
        'uid',
        'work_id',
    ];

    /**
     * 获取设备模型类名
     *
     * @return string
     */
    protected function getDeviceModelClass(): string
    {
        return Ymsb::class;
    }

    /**
     * 获取设备类型
     *
     * @return string
     */
    public function getDeviceType(): string
    {
        return '研磨机';
    }

    /**
     * 获取对应的工单
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_id', 'id');
    }
}