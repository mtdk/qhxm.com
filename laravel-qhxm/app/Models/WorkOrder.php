<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 工单模型
 */
class WorkOrder extends Model
{
    use HasFactory;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'work_order';

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
        'pro_id',
        'bath_number',
        'remarks',
        'work_state',
        'technology_target',
    ];

    /**
     * 获取工艺目标文本
     *
     * @return string
     */
    public function getTechnologyTargetTextAttribute()
    {
        return match($this->technology_target) {
            'FS' => '分散',
            'YM' => '研磨',
            default => '未知',
        };
    }

    /**
     * 获取工单状态文本
     *
     * @return string
     */
    public function getWorkStateTextAttribute()
    {
        return match($this->work_state) {
            0 => '未领取',
            1 => '已领取',
            2 => '进行中',
            3 => '已完成',
            default => '未知状态',
        };
    }

    /**
     * 获取对应的运行记录
     */
    public function deviceRecords()
    {
        $relation = null;
        
        if ($this->technology_target === 'FS') {
            $relation = $this->hasMany(FssbRecord::class, 'work_id', 'id');
        } elseif ($this->technology_target === 'YM') {
            // YmsbRecord 模型需要后续创建
            $relation = $this->hasMany(YmsbRecord::class, 'work_id', 'id');
        }
        
        return $relation;
    }

    /**
     * 获取未领取的工单
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnclaimed($query)
    {
        return $query->where('work_state', 0);
    }

    /**
     * 获取指定工艺的工单
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $technology
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTechnology($query, $technology)
    {
        return $query->where('technology_target', $technology);
    }
}