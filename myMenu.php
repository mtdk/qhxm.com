<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">系统首页</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNavDarkDropdown"
                aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <!-- 公共部分 -->
                <li class="nav-item dropdown">
                    <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        用户信息
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="user_pwd_up.php">密码修改</a></li>
                        <li><a class="dropdown-item" href="user_info_up.php">用户名修改</a></li>
                    </ul>
                </li>
                <!-- 行政部 -->
                <?php if ($department_id == 1 && $role_id == 1): ?>
                    <li class="nav-item dropdown">

                    </li>
                <?php elseif ($department_id == 1 && $role_id == 2): ?>
                    <li class="nav-item dropdown">

                    </li>
                <?php endif; ?>
                <!-- 生产部 -->
                <?php if ($department_id == 2 && $role_id == 1): ?>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            工单管理
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="work_order_show.php">我要领单</a></li>
                            <li><a class="dropdown-item" href="all_device.php">我要关机</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            手动登记
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="fqpfsbjl.php">废气设备登记</a></li>
                            <li><a class="dropdown-item" href="kyjsbjl.php">空压机登记</a></li>
                            <li><a class="dropdown-item" href="bsjsbjl.php">冰水机登记</a></li>
                            <li><a class="dropdown-item" href="fssbjl.php">分散登记</a></li>
                            <li><a class="dropdown-item" href="ymsbjl.php">研磨登记</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            设备报修
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="device_repair.php">报修登记</a></li>
                        </ul>
                    </li>
                <?php elseif ($department_id == 2 && $role_id == 2): ?>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            工单管理
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="work_order_show.php">我要领单</a></li>
                            <li><a class="dropdown-item" href="all_device.php">我要关机</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            手动登记
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="fqpfsbjl.php">废气设备登记</a></li>
                            <li><a class="dropdown-item" href="kyjsbjl.php">空压机登记</a></li>
                            <li><a class="dropdown-item" href="bsjsbjl.php">冰水机登记</a></li>
                            <li><a class="dropdown-item" href="fssbjl.php">分散登记</a></li>
                            <li><a class="dropdown-item" href="ymsbjl.php">研磨登记</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            手动关机
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/fssb_rds_up.php">分散关机</a></li>
                            <li><a class="dropdown-item" href="/ymsb_rds_up.php">研磨关机</a></li>
                            <li><a class="dropdown-item" href="/fqpfsb_rds_up.php">废气设备关机</a></li>
                            <li><a class="dropdown-item" href="/bsj_rds_up.php">冰水机关机</a></li>
                            <li><a class="dropdown-item" href="/kyj_rds_up.php">空压机关机</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            设备管理
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="device_repair.php">报修登记</a></li>
                            <li><a class="dropdown-item" href="device_repair_show.php">报修审核</a></li>
                            <li><a class="dropdown-item" href="device_repair_over_list.php">完成确认</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- 仓储部 -->
                <?php if ($department_id == 3 && $role_id == 1): ?>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            仓储管理
                        </button>
                    </li>
                <?php elseif ($department_id == 3 && $role_id == 2): ?>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            仓储测试
                        </button>
                    </li>
                <?php endif; ?>
                <!-- 行政部-设备科 -->
                <?php if ($department_id == 4 && $role_id == 1): ?>
                <?php elseif ($department_id == 4 && $role_id == 2): ?>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            记录打印
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="fssb_rds_query.php">分散机记录打印</a></li>
                            <li><a class="dropdown-item" href="ymsb_rds_query.php">研磨机记录打印</a></li>
                            <li><a class="dropdown-item" href="kyjsb_rds_query.php">空压机记录打印</a></li>
                            <li><a class="dropdown-item" href="bsjsb_rds_query.php">冰水机记录打印</a></li>
                            <li><a class="dropdown-item" href="fqsb_rds_query.php">废气设备记录打印</a></li>
                        </ul>
                    </li>
                <li class="nav-item dropdown">
                    <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        设备维修
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="device_fix_receive_show.php">领单维修</a></li>
                        <li><a class="dropdown-item" href="device_fix_over_list.php">完成确认</a></li>
                        <li><a class="dropdown-item" href="device_fix_query.php">查询与打印</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <!-- 生产技术部-调度科 -->
                <?php if ($department_id == 5 && $role_id == 1): ?>
                <?php elseif ($department_id == 5 && $role_id == 2): ?>
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            工单管理
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="work_order_insert.php">工单登记</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            <a href="logout.php" class="d-flex btn btn-outline-success">退出</a>
        </div>
    </div>
</nav>