<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 设备基础模型
 * 
 * 所有设备表的通用模型，具体设备通过设置$table属性指定表名
 */
abstract class Device extends Model
{
    use HasFactory;

    /**
     * 模型的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 指示模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 可批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'machine_id',
        'machine_name',
        'machine_status',
    ];

    /**
     * 获取设备的运行记录
     */
    public function records()
    {
        return $this->hasMany(DeviceRecord::class, 'machine_id', 'machine_id');
    }

    /**
     * 获取当前正在运行的设备
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRunning($query)
    {
        return $query->where('machine_status', 'T');
    }

    /**
     * 获取设备状态的中文显示
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        return $this->machine_status === 'T' ? '运行中' : '已关机';
    }

    /**
     * 获取设备类型
     *
     * @return string
     */
    abstract public function getDeviceType(): string;
}