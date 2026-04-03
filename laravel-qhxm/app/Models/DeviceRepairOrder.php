<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 设备维修订单模型
 */
class DeviceRepairOrder extends Model
{
    use HasFactory;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'tb_device_repair_order';

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
        'repair_id',
        'device_id',
        'device_name',
        'device_type',
        'use_department',
        'use_name',
        'brief_content',
        'apply_time',
        'auditor_name',
        'audit_time',
        'repairer_department',
        'repairer_name',
        'repair_start_time',
        'repair_end_time',
        'repair_content',
        'repair_status',
        'manager_check_time',
        'progress',
        'repair_msg',
    ];

    /**
     * 获取设备类型文本
     *
     * @return string
     */
    public function getDeviceTypeTextAttribute()
    {
        return match($this->device_type) {
            'FS' => '分散机',
            'YM' => '研磨机',
            'KY' => '空压机',
            'BS' => '冰水机',
            'FQ' => '废气设备',
            default => '未知设备',
        };
    }

    /**
     * 获取维修状态文本
     *
     * @return string
     */
    public function getRepairStatusTextAttribute()
    {
        return match($this->repair_status) {
            0 => '提交维修',
            1 => '主管确认',
            2 => '机修确认',
            3 => '机修完成',
            4 => '主管确认',
            default => '未知状态',
        };
    }

    /**
     * 获取进度文本
     *
     * @return string
     */
    public function getProgressTextAttribute()
    {
        return $this->progress . '%';
    }

    /**
     * 获取维修状态颜色
     *
     * @return string
     */
    public function getRepairStatusColorAttribute()
    {
        return match($this->repair_status) {
            0 => 'secondary',
            1 => 'info',
            2 => 'primary',
            3 => 'warning',
            4 => 'success',
            default => 'light',
        };
    }

    /**
     * 获取未完成的维修订单
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUncompleted($query)
    {
        return $query->where('repair_status', '<>', 4);
    }

    /**
     * 获取指定状态的维修订单
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('repair_status', $status);
    }

    /**
     * 获取设备类型对应的设备模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function device()
    {
        $modelClass = match($this->device_type) {
            'FS' => Fssb::class,
            'YM' => Ymsb::class,
            'KY' => Kyjsb::class,
            'BS' => Bsjsb::class,
            'FQ' => Fqsb::class,
            default => null,
        };

        if (!$modelClass) {
            return null;
        }

        return $this->belongsTo($modelClass, 'device_id', 'machine_id');
    }
}