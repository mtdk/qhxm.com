<?php

namespace App\Models;

/**
 * 分散机运行记录模型
 */
class FssbRecord extends DeviceRecord
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'fssbrecords';

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
        'technology_target',
    ];

    /**
     * 获取设备模型类名
     *
     * @return string
     */
    protected function getDeviceModelClass(): string
    {
        return Fssb::class;
    }

    /**
     * 获取设备类型
     *
     * @return string
     */
    public function getDeviceType(): string
    {
        return '分散机';
    }

    /**
     * 获取对应的工单
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_id', 'id');
    }

    /**
     * 获取工艺目标文本
     *
     * @return string
     */
    public function getTechnologyTargetTextAttribute()
    {
        return $this->technology_target === 'FS' ? '分散' : '未知';
    }
}