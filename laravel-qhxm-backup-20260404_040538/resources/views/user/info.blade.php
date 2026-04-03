<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QHXM管理系统 - 用户信息</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .main-container {
            margin-top: 2rem;
        }
        .section-title {
            color: #34495e;
            border-left: 4px solid #3498db;
            padding-left: 1rem;
            margin: 2rem 0 1rem 0;
        }
        .user-info-card {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .user-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .user-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 3rem;
        }
        .info-row {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- 导航栏 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-house-gear"></i> QHXM管理系统
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @if(isset($menuItems))
                        @foreach($menuItems as $menu)
                            @if($menu['type'] === 'link')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ $menu['url'] }}">
                                        <i class="{{ $menu['icon'] }}"></i> {{ $menu['label'] }}
                                    </a>
                                </li>
                            @elseif($menu['type'] === 'dropdown')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        <i class="{{ $menu['icon'] }}"></i> {{ $menu['label'] }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach($menu['items'] as $submenu)
                                            <li><a class="dropdown-item" href="{{ $submenu['url'] }}"><i class="{{ $submenu['icon'] }}"></i> {{ $submenu['label'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    @else
                        <!-- 默认菜单（如果没有传递menuItems） -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="bi bi-house"></i> 首页
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> 用户中心
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active" href="{{ route('user.info') }}"><i class="bi bi-person"></i> 用户信息</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.name.change') }}"><i class="bi bi-pencil-square"></i> 用户姓名修改</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.password.change') }}"><i class="bi bi-key"></i> 密码修改</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-3">
                        <i class="bi bi-person"></i> {{ session('uname', '用户') }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm px-3">
                            <i class="bi bi-box-arrow-right me-1"></i> 退出
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- 主要内容 -->
    <main class="container main-container">
        <h3 class="section-title">
            <i class="bi bi-person"></i> 用户信息
        </h3>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card user-info-card">
                    <div class="user-header">
                        <div class="user-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h3>{{ $user->uname }}</h3>
                        <p class="mb-0">{{ $user->uid }}</p>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-4 info-label">
                                    <i class="bi bi-person-badge me-2"></i>用户ID
                                </div>
                                <div class="col-md-8 info-value">
                                    {{ $user->uid }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-4 info-label">
                                    <i class="bi bi-person-vcard me-2"></i>用户姓名
                                </div>
                                <div class="col-md-8 info-value">
                                    {{ $user->uname }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-4 info-label">
                                    <i class="bi bi-building me-2"></i>部门
                                </div>
                                <div class="col-md-8 info-value">
                                    @if($user->department_id && $user->department)
                                        {{ $user->department->department_name }}
                                    @elseif($user->department_id)
                                        部门ID：{{ $user->department_id }}（未找到部门名称）
                                    @else
                                        未分配部门
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($user->role_id)
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-4 info-label">
                                    <i class="bi bi-shield-check me-2"></i>用户角色
                                </div>
                                <div class="col-md-8 info-value">
                                    @php
                                        $roles = [
                                            1 => '系统管理员',
                                            2 => '普通用户',
                                            3 => '设备管理员',
                                            4 => '工单处理员'
                                        ];
                                        echo $roles[$user->role_id] ?? '未知角色';
                                    @endphp
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-4 info-label">
                                    <i class="bi bi-check-circle me-2"></i>用户状态
                                </div>
                                <div class="col-md-8 info-value">
                                    @php
                                        $statuses = [
                                            0 => '禁用',
                                            1 => '正常',
                                            2 => '待激活',
                                            3 => '锁定'
                                        ];
                                        echo $statuses[$user->userstate_id] ?? '未知状态';
                                    @endphp
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-4 info-label">
                                    <i class="bi bi-calendar me-2"></i>创建时间
                                </div>
                                <div class="col-md-8 info-value">
                                    {{ $user->registration_time ? \Carbon\Carbon::parse($user->registration_time)->format('Y-m-d H:i:s') : '未记录' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="{{ route('user.name.change') }}" class="btn btn-info w-100">
                                    <i class="bi bi-pencil-square me-2"></i>姓名修改
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('user.password.change') }}" class="btn btn-primary w-100">
                                    <i class="bi bi-key me-2"></i>修改密码
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-house me-2"></i>返回首页
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- 页脚 -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">
                <i class="bi bi-c-circle"></i> QHXM管理系统 &copy; {{ date('Y') }}
            </span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>