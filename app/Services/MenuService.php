<?php

namespace App\Services;

use App\Models\UserTb;

class MenuService
{
    /**
     * 根据用户部门和角色获取菜单项
     */
    public static function getMenuItems(UserTb $user): array
    {
        $departmentId = $user->department_id ?? 0;
        $roleId = $user->role_id ?? 0;
        
        $menuItems = [
            [
                'type' => 'link',
                'label' => '首页',
                'icon' => 'bi bi-house',
                'url' => route('home'),
                'permission' => true, // 所有人都可以访问首页
            ],
        ];

        // 根据部门和角色添加特定菜单
        $departmentMenus = self::getDepartmentMenus($departmentId, $roleId);
        $menuItems = array_merge($menuItems, $departmentMenus);

        // 添加用户中心菜单（所有人都可以访问）
        $menuItems[] = [
            'type' => 'dropdown',
            'label' => '用户中心',
            'icon' => 'bi bi-person-circle',
            'items' => [
                [
                    'label' => '用户信息',
                    'icon' => 'bi bi-person',
                    'url' => route('user.info'),
                ],
                [
                    'label' => '用户姓名修改',
                    'icon' => 'bi bi-pencil-square',
                    'url' => route('user.name.change'),
                ],
                [
                    'label' => '密码修改',
                    'icon' => 'bi bi-key',
                    'url' => route('user.password.change'),
                ],
            ],
            'permission' => true,
        ];

        // 过滤掉没有权限的菜单项
        return array_filter($menuItems, fn($item) => $item['permission']);
    }

    /**
     * 根据部门和角色获取特定菜单
     */
    private static function getDepartmentMenus(int $departmentId, int $roleId): array
    {
        $menus = [];

        // 生产技术部 (department_id = 2)
        if ($departmentId == 2) {
            // 员工 (role_id = 1)
            if ($roleId == 1) {
                $menus = array_merge($menus, [
                    [
                        'type' => 'dropdown',
                        'label' => '工单管理',
                        'icon' => 'bi bi-clipboard-check',
                        'items' => [
                            [
                                'label' => '我要领单',
                                'icon' => 'bi bi-list-check',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '我要关机',
                                'icon' => 'bi bi-power',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                        ],
                        'permission' => true,
                    ],
                    [
                        'type' => 'dropdown',
                        'label' => '手动登记',
                        'icon' => 'bi bi-pencil-square',
                        'items' => [
                            [
                                'label' => '废气设备登记',
                                'icon' => 'bi bi-fan',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '空压机登记',
                                'icon' => 'bi bi-compass',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '冰水机登记',
                                'icon' => 'bi bi-snow',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '分散登记',
                                'icon' => 'bi bi-shuffle',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '研磨登记',
                                'icon' => 'bi bi-gear-wide',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                        ],
                        'permission' => true,
                    ],
                    [
                        'type' => 'dropdown',
                        'label' => '设备报修',
                        'icon' => 'bi bi-tools',
                        'items' => [
                            [
                                'label' => '报修登记',
                                'icon' => 'bi bi-plus-circle',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                        ],
                        'permission' => true,
                    ],
                ]);
            }
            // 管理员 (role_id = 2)
            elseif ($roleId == 2) {
                $menus = array_merge($menus, [
                    [
                        'type' => 'dropdown',
                        'label' => '工单管理',
                        'icon' => 'bi bi-clipboard-check',
                        'items' => [
                            [
                                'label' => '我要领单',
                                'icon' => 'bi bi-list-check',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '我要关机',
                                'icon' => 'bi bi-power',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                        ],
                        'permission' => true,
                    ],
                    [
                        'type' => 'dropdown',
                        'label' => '手动登记',
                        'icon' => 'bi bi-pencil-square',
                        'items' => [
                            [
                                'label' => '废气设备登记',
                                'icon' => 'bi bi-fan',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '空压机登记',
                                'icon' => 'bi bi-compass',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '冰水机登记',
                                'icon' => 'bi bi-snow',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '分散登记',
                                'icon' => 'bi bi-shuffle',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '研磨登记',
                                'icon' => 'bi bi-gear-wide',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                        ],
                        'permission' => true,
                    ],
                    [
                        'type' => 'dropdown',
                        'label' => '手动关机',
                        'icon' => 'bi bi-power',
                        'items' => [
                            [
                                'label' => '分散关机',
                                'icon' => 'bi bi-shuffle',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '研磨关机',
                                'icon' => 'bi bi-gear-wide',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '废气设备关机',
                                'icon' => 'bi bi-fan',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '冰水机关机',
                                'icon' => 'bi bi-snow',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '空压机关机',
                                'icon' => 'bi bi-compass',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                        ],
                        'permission' => true,
                    ],
                    [
                        'type' => 'dropdown',
                        'label' => '设备管理',
                        'icon' => 'bi bi-gear',
                        'items' => [
                            [
                                'label' => '报修登记',
                                'icon' => 'bi bi-plus-circle',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '报修审核',
                                'icon' => 'bi bi-check-circle',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                            [
                                'label' => '完成确认',
                                'icon' => 'bi bi-check-all',
                                'url' => '#', // TODO: 替换为实际路由
                            ],
                        ],
                        'permission' => true,
                    ],
                ]);
            }
        }
        // 行政部-设备科 (department_id = 4)
        elseif ($departmentId == 4 && $roleId == 2) {
            $menus = array_merge($menus, [
                [
                    'type' => 'dropdown',
                    'label' => '记录打印',
                    'icon' => 'bi bi-printer',
                    'items' => [
                        [
                            'label' => '分散机记录打印',
                            'icon' => 'bi bi-printer-fill',
                            'url' => '#', // TODO: 替换为实际路由
                        ],
                        [
                            'label' => '研磨机记录打印',
                            'icon' => 'bi bi-printer-fill',
                            'url' => '#', // TODO: 替换为实际路由
                        ],
                        [
                            'label' => '空压机记录打印',
                            'icon' => 'bi bi-printer-fill',
                            'url' => '#', // TODO: 替换为实际路由
                        ],
                        [
                            'label' => '冰水机记录打印',
                            'icon' => 'bi bi-printer-fill',
                            'url' => '#', // TODO: 替换为实际路由
                        ],
                        [
                            'label' => '废气设备记录打印',
                            'icon' => 'bi bi-printer-fill',
                            'url' => '#', // TODO: 替换为实际路由
                        ],
                    ],
                    'permission' => true,
                ],
                [
                    'type' => 'dropdown',
                    'label' => '设备维修',
                    'icon' => 'bi bi-tools',
                    'items' => [
                        [
                            'label' => '领单维修',
                            'icon' => 'bi bi-clipboard-check',
                            'url' => '#', // TODO: 替换为实际路由
                        ],
                        [
                            'label' => '完成确认',
                            'icon' => 'bi bi-check-all',
                            'url' => '#', // TODO: 替换为实际路由
                        ],
                        [
                            'label' => '查询与打印',
                            'icon' => 'bi bi-search',
                            'url' => '#', // TODO: 替换为实际路由
                        ],
                    ],
                    'permission' => true,
                ],
            ]);
        }
        // 生产技术部-调度科 (department_id = 5 && role_id = 2)
        elseif ($departmentId == 5 && $roleId == 2) {
            $menus[] = [
                'type' => 'dropdown',
                'label' => '工单管理',
                'icon' => 'bi bi-clipboard-check',
                'items' => [
                    [
                        'label' => '工单登记',
                        'icon' => 'bi bi-plus-circle',
                        'url' => '#', // TODO: 替换为实际路由
                    ],
                ],
                'permission' => true,
            ];
        }
        // 仓储部 (department_id = 3)
        elseif ($departmentId == 3) {
            if ($roleId == 1) {
                $menus[] = [
                    'type' => 'dropdown',
                    'label' => '仓储管理',
                    'icon' => 'bi bi-box-seam',
                    'items' => [],
                    'permission' => true,
                ];
            } elseif ($roleId == 2) {
                $menus[] = [
                    'type' => 'dropdown',
                    'label' => '仓储测试',
                    'icon' => 'bi bi-box-seam',
                    'items' => [],
                    'permission' => true,
                ];
            }
        }

        return $menus;
    }

    /**
     * 检查用户是否有权限访问某个菜单
     */
    public static function hasPermission(UserTb $user, string $menuKey): bool
    {
        $departmentId = $user->department_id ?? 0;
        $roleId = $user->role_id ?? 0;

        // 权限规则定义
        $permissionRules = [
            // 首页和用户信息所有人都可以访问
            'home' => true,
            'user.info' => true,
            'user.name.change' => true,
            'user.password.change' => true,

            // 生产技术部权限
            'work_order.claim' => $departmentId == 2 && in_array($roleId, [1, 2]),
            'work_order.shutdown' => $departmentId == 2 && in_array($roleId, [1, 2]),
            'device.register.fqpfsb' => $departmentId == 2 && in_array($roleId, [1, 2]),
            'device.register.kyjsb' => $departmentId == 2 && in_array($roleId, [1, 2]),
            'device.register.bsjsb' => $departmentId == 2 && in_array($roleId, [1, 2]),
            'device.register.fssb' => $departmentId == 2 && in_array($roleId, [1, 2]),
            'device.register.ymsb' => $departmentId == 2 && in_array($roleId, [1, 2]),
            'device.repair.register' => $departmentId == 2 && in_array($roleId, [1, 2]),
            'device.report.review' => $departmentId == 2 && $roleId == 2,
            'device.report.confirm' => $departmentId == 2 && $roleId == 2,
            'manual.shutdown.fssb' => $departmentId == 2 && $roleId == 2,
            'manual.shutdown.ymsb' => $departmentId == 2 && $roleId == 2,
            'manual.shutdown.fqpfsb' => $departmentId == 2 && $roleId == 2,
            'manual.shutdown.bsj' => $departmentId == 2 && $roleId == 2,
            'manual.shutdown.kyj' => $departmentId == 2 && $roleId == 2,

            // 行政部-设备科权限
            'record.print.fssb' => $departmentId == 4 && $roleId == 2,
            'record.print.ymsb' => $departmentId == 4 && $roleId == 2,
            'record.print.kyjsb' => $departmentId == 4 && $roleId == 2,
            'record.print.bsjsb' => $departmentId == 4 && $roleId == 2,
            'record.print.fqsb' => $departmentId == 4 && $roleId == 2,
            'device.repair.receive' => $departmentId == 4 && $roleId == 2,
            'device.repair.confirm' => $departmentId == 4 && $roleId == 2,
            'device.repair.query' => $departmentId == 4 && $roleId == 2,

            // 生产技术部-调度科权限
            'work_order.register' => $departmentId == 5 && $roleId == 2,

            // 仓储部权限
            'warehouse.manage' => $departmentId == 3 && $roleId == 1,
            'warehouse.test' => $departmentId == 3 && $roleId == 2,
        ];

        return $permissionRules[$menuKey] ?? false;
    }

    /**
     * 获取用户可访问的路由列表
     */
    public static function getAllowedRoutes(UserTb $user): array
    {
        $routes = [
            'home',
            'user.info',
            'user.name.change',
            'user.password.change',
            'logout',
        ];

        $departmentId = $user->department_id ?? 0;
        $roleId = $user->role_id ?? 0;

        // 根据部门和角色添加路由
        if ($departmentId == 2) {
            $routes = array_merge($routes, [
                'work_order.claim',
                'work_order.shutdown',
                'device.register.fqpfsb',
                'device.register.kyjsb',
                'device.register.bsjsb',
                'device.register.fssb',
                'device.register.ymsb',
                'device.repair.register',
            ]);

            if ($roleId == 2) {
                $routes = array_merge($routes, [
                    'device.report.review',
                    'device.report.confirm',
                    'manual.shutdown.fssb',
                    'manual.shutdown.ymsb',
                    'manual.shutdown.fqpfsb',
                    'manual.shutdown.bsj',
                    'manual.shutdown.kyj',
                ]);
            }
        }

        if ($departmentId == 4 && $roleId == 2) {
            $routes = array_merge($routes, [
                'record.print.fssb',
                'record.print.ymsb',
                'record.print.kyjsb',
                'record.print.bsjsb',
                'record.print.fqsb',
                'device.repair.receive',
                'device.repair.confirm',
                'device.repair.query',
            ]);
        }

        if ($departmentId == 5 && $roleId == 2) {
            $routes[] = 'work_order.register';
        }

        if ($departmentId == 3) {
            if ($roleId == 1) {
                $routes[] = 'warehouse.manage';
            } elseif ($roleId == 2) {
                $routes[] = 'warehouse.test';
            }
        }

        return $routes;
    }
}