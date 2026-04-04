<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 设备运行记录基础模型
 */
abstract class DeviceRecord extends Model
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
        'register_date',
        'register_time',
        'shutdown_time',
        'total_duration',
        'machine_status',
        'uid',
    ];

    /**
     * 获取对应的设备
     */
    public function device()
    {
        return $this->belongsTo($this->getDeviceModelClass(), 'machine_id', 'machine_id');
    }

    /**
     * 获取设备模型类名
     *
     * @return string
     */
    abstract protected function getDeviceModelClass(): string;

    /**
     * 获取设备类型
     *
     * @return string
     */
    abstract public function getDeviceType(): string;

    /**
     * 获取运行状态的中文显示
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        return $this->machine_status === '开机' ? '运行中' : '已关机';
    }

    /**
     * 获取运行时长（小时）
     *
     * @return float|null
     */
    public function getDurationHoursAttribute()
    {
        if (!$this->total_duration) {
            return null;
        }
        return round($this->total_duration / 60, 2);
    }

    /**
     * 创建一个新的 Eloquent 集合实例
     *
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new \Illuminate\Database\Eloquent\Collection($models);
    }

    /**
     * 获取当天正在运行的记录
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRunningToday($query)
    {
        return $query->where('machine_status', '开机')
                    ->where('register_date', date('Y-m-d'));
    }
}