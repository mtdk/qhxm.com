<?php

namespace App\Models;

/**
 * 废气设备模型
 */
class Fqsb extends Device
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'fqsb';

    /**
     * 获取设备类型
     *
     * @return string
     */
    public function getDeviceType(): string
    {
        return '废气设备';
    }

    /**
     * 获取设备类型代码
     *
     * @return string
     */
    public function getDeviceTypeCode(): string
    {
        return 'FQ';
    }

    /**
     * 获取该设备的运行记录
     */
    public function records()
    {
        return $this->hasMany(FqsbRecord::class, 'machine_id', 'machine_id');
    }
}