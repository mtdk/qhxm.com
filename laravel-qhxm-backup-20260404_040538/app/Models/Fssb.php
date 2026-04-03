<?php

namespace App\Models;

/**
 * 分散机设备模型
 */
class Fssb extends Device
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'fssb';

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
     * 获取设备类型代码
     *
     * @return string
     */
    public function getDeviceTypeCode(): string
    {
        return 'FS';
    }

    /**
     * 获取该设备的运行记录
     */
    public function records()
    {
        return $this->hasMany(FssbRecord::class, 'machine_id', 'machine_id');
    }
}