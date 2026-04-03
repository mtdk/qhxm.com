<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'tb_role';

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
        'role_id',
        'role_name',
    ];

    /**
     * 获取该角色的用户
     */
    public function users()
    {
        return $this->hasMany(UserTb::class, 'role_id', 'role_id');
    }
}