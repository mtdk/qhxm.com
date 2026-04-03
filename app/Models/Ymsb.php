<?php

namespace App\Models;

/**
 * 研磨机设备模型
 */
class Ymsb extends Device
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'ymsb';

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
     * 获取设备类型代码
     *
     * @return string
     */
    public function getDeviceTypeCode(): string
    {
        return 'YM';
    }

    /**
     * 获取该设备的运行记录
     */
    public function records()
    {
        return $this->hasMany(YmsbRecord::class, 'machine_id', 'machine_id');
    }
}