<?php

namespace App\Services;

use App\Models\UserTb;

class MenuService
{
    /**
     * 根据用户角色获取菜单项
     *
     * @param UserTb|null $user
     * @return array
     */
    public static function getMenuForUser($user = null)
    {
        if (!$user) {
            // 未登录用户
            return self::getGuestMenu();
        }
        
        $roleId = $user->role_id ?? 0;
        
        // 根据角色ID返回对应的菜单
        switch ($roleId) {
            case 1: // 管理员
                return self::getAdminMenu();
            case 2: // 生产计划员（工单创建人员）
                return self::getPlannerMenu();
            case 3: // 生产技术员
                return self::getTechnicianMenu();
            default:
                return self::getGuestMenu();
        }
    }
    
    /**
     * 获取用户菜单项（兼容旧代码）
     *
     * @param UserTb|null $user
     * @return array
     */
    public static function getMenuItems($user = null)
    {
        return self::getMenuForUser($user);
    }
    
    /**
     * 管理员菜单
     */
    private static function getAdminMenu()
    {
        return [
            [
                'name' => '首页',
                'route' => 'home',
                'icon' => 'bi-house',
                'active_patterns' => ['home']
            ],
            [
                'name' => '设备管理',
                'route' => 'devices.index',
                'icon' => 'bi-device-hdd',
                'active_patterns' => ['devices.*', 'device.*']
            ],
            [
                'name' => '工单管理',
                'route' => 'work-orders.index',
                'icon' => 'bi-clipboard-check',
                'active_patterns' => ['work-orders.*', 'work-order.*']
            ],
            [
                'name' => '用户管理',
                'route' => '#',
                'icon' => 'bi-people',
                'active_patterns' => ['users.*', 'user.*']
            ],
            [
                'name' => '系统设置',
                'route' => '#',
                'icon' => 'bi-gear',
                'active_patterns' => ['settings.*']
            ],
        ];
    }
    
    /**
     * 生产计划员菜单（工单创建人员）
     */
    private static function getPlannerMenu()
    {
        return [
            [
                'name' => '首页',
                'route' => 'home',
                'icon' => 'bi-house',
                'active_patterns' => ['home', 'dashboard', 'realtime-status']
            ],
            [
                'name' => '工单管理',
                'route' => 'work-orders.index',
                'icon' => 'bi-clipboard-check',
                'active_patterns' => ['work-orders.*', 'work-order.*']
            ],
        ];
    }
    
    /**
     * 生产技术员菜单
     */
    private static function getTechnicianMenu()
    {
        return [
            [
                'name' => '首页',
                'route' => 'home',
                'icon' => 'bi-house',
                'active_patterns' => ['home']
            ],
            [
                'name' => '我的工单',
                'route' => '#',
                'icon' => 'bi-clipboard',
                'active_patterns' => ['my-work-orders.*']
            ],
            [
                'name' => '设备操作',
                'route' => '#',
                'icon' => 'bi-tools',
                'active_patterns' => ['device-operations.*']
            ],
        ];
    }
    
    /**
     * 访客菜单（未登录用户）
     */
    private static function getGuestMenu()
    {
        return [
            [
                'name' => '首页',
                'route' => 'home',
                'icon' => 'bi-house',
                'active_patterns' => ['home']
            ],
            [
                'name' => '登录',
                'route' => 'login',
                'icon' => 'bi-box-arrow-in-right',
                'active_patterns' => ['login']
            ],
        ];
    }
    
    /**
     * 检查当前路由是否匹配菜单项
     *
     * @param array $menuItem
     * @param string $currentRoute
     * @return bool
     */
    public static function isActive($menuItem, $currentRoute)
    {
        if (!isset($menuItem['active_patterns'])) {
            return false;
        }
        
        foreach ($menuItem['active_patterns'] as $pattern) {
            if (fnmatch($pattern, $currentRoute)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 获取当前用户（从session中）
     *
     * @return UserTb|null
     */
    public static function getCurrentUser()
    {
        // 当前系统使用session存储用户信息
        // 用户ID可能存储在session('uid')中
        $userId = session('uid');
        
        if ($userId) {
            return UserTb::where('uid', $userId)->first();
        }
        
        // 如果session中没有uid，尝试从其他session字段获取
        // 或者返回一个模拟用户用于测试
        return self::getMockUserForTesting();
    }
    
    /**
     * 获取测试用的模拟用户（用于开发测试）
     *
     * @return UserTb|null
     */
    private static function getMockUserForTesting()
    {
        // 这里可以根据需要返回不同角色的用户进行测试
        // 生产环境中应该注释掉这部分代码
        
        // 测试用：返回生产计划员（role_id = 2）
        $mockUser = new UserTb();
        $mockUser->role_id = 2; // 生产计划员
        $mockUser->uid = 'test_planner';
        $mockUser->uname = '测试计划员';
        
        return $mockUser;
        
        // 如果要测试管理员，取消下面的注释
        // $mockUser = new UserTb();
        // $mockUser->role_id = 1; // 管理员
        // $mockUser->uid = 'test_admin';
        // $mockUser->uname = '测试管理员';
        // return $mockUser;
        
        // 如果要测试未登录状态，返回null
        // return null;
    }
}