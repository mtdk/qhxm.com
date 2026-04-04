@php
use App\Services\MenuService;

// 获取当前用户
$user = MenuService::getCurrentUser();

// 获取用户对应的菜单
$menuItems = MenuService::getMenuForUser($user);

// 获取当前路由名称
$currentRoute = Route::currentRouteName() ?? '';

// 获取用户名（从session中）
$userName = session('uname', '用户');
@endphp

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
                @foreach($menuItems as $item)
                    @php
                        $isActive = MenuService::isActive($item, $currentRoute);
                        $route = $item['route'] ?? '#';
                        $href = $route === '#' ? '#' : route($route);
                    @endphp
                    <li class="nav-item {{ $isActive ? 'active' : '' }}">
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $href }}">
                            <i class="bi {{ $item['icon'] }}"></i> {{ $item['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <ul class="navbar-nav">
                @if($user)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ $userName }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> 个人资料</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> 退出登录
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> 登录
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>