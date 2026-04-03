<?php

namespace App\Models;

/**
 * 冰水机设备模型
 */
class Bsjsb extends Device
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'bsjsb';

    /**
     * 获取设备类型
     *
     * @return string
     */
    public function getDeviceType(): string
    {
        return '冰水机';
    }

    /**
     * 获取设备类型代码
     *
     * @return string
     */
    public function getDeviceTypeCode(): string
    {
        return 'BS';
    }

    /**
     * 获取该设备的运行记录
     */
    public function records()
    {
        return $this->hasMany(BsjsbRecord::class, 'machine_id', 'machine_id');
    }
}