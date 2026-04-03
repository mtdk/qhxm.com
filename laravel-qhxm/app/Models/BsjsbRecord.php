<?php

namespace App\Models;

/**
 * 冰水机运行记录模型
 */
class BsjsbRecord extends DeviceRecord
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'bsjrecords';

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
        'machine_status',
        'uid',
    ];

    /**
     * 获取设备模型类名
     *
     * @return string
     */
    protected function getDeviceModelClass(): string
    {
        return Bsjsb::class;
    }

    /**
     * 获取设备类型
     *
     * @return string
     */
    public function getDeviceType(): string
    {
        return '冰水机';
    }
}