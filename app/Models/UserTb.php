<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserTb extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'usertb';

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
        'uid',
        'uname',
        'upassword',
        'department_id',
        'role_id',
        'userstate_id',
        'remember_token',
        'registration_time',
    ];

    /**
     * 需要隐藏的属性
     *
     * @var array
     */
    protected $hidden = [
        'upassword',
        'remember_token',
    ];

    /**
     * 获取用户的部门
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /**
     * 获取用户的角色
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * 获取用户状态
     */
    public function userState()
    {
        return $this->belongsTo(UserState::class, 'userstate_id', 'userstate_id');
    }

    /**
     * 获取用户的密码属性
     *
     * @param  string  $value
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->upassword;
    }

    /**
     * 获取用户的登录标识
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'uid';
    }

    /**
     * 获取用户的登录标识值
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->uid;
    }
}