<?php

namespace App\Models;

/**
 * 空压机设备模型
 */
class Kyjsb extends Device
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'kyjsb';

    /**
     * 获取设备类型
     *
     * @return string
     */
    public function getDeviceType(): string
    {
        return '空压机';
    }

    /**
     * 获取设备类型代码
     *
     * @return string
     */
    public function getDeviceTypeCode(): string
    {
        return 'KY';
    }

    /**
     * 获取该设备的运行记录
     */
    public function records()
    {
        return $this->hasMany(KyjsbRecord::class, 'machine_id', 'machine_id');
    }
}