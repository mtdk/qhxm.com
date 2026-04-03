<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QHXM管理系统 - 用户姓名修改</title>
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
        .name-change-card {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .name-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        .name-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .current-name {
            font-size: 1.2rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        .name-change-hint {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
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
                                    <a class="nav-link dropdown-toggle {{ request()->routeIs('user.name.change') && $menu['label'] === '用户中心' ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                                        <i class="{{ $menu['icon'] }}"></i> {{ $menu['label'] }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach($menu['items'] as $submenu)
                                            <li><a class="dropdown-item {{ request()->routeIs('user.name.change') && $submenu['label'] === '用户姓名修改' ? 'active' : '' }}" href="{{ $submenu['url'] }}"><i class="{{ $submenu['icon'] }}"></i> {{ $submenu['label'] }}</a></li>
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
                                <li><a class="dropdown-item" href="{{ route('user.info') }}"><i class="bi bi-person"></i> 用户信息</a></li>
                                <li><a class="dropdown-item active" href="{{ route('user.name.change') }}"><i class="bi bi-pencil-square"></i> 用户姓名修改</a></li>
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
            <i class="bi bi-pencil-square"></i> 修改用户姓名
        </h3>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card name-change-card">
                    <div class="name-header">
                        <div class="name-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h4>用户姓名修改</h4>
                        <p class="mb-0">请填写新的用户姓名</p>
                    </div>
                    
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-person me-2"></i>当前用户姓名
                            </label>
                            <div class="current-name">
                                <strong>{{ $user->uname }}</strong>
                            </div>
                            <div class="name-change-hint">
                                <i class="bi bi-info-circle me-1"></i> 您的用户ID：<code>{{ $user->uid }}</code> 不能修改
                            </div>
                        </div>

                        <form method="POST" action="{{ route('user.name.update') }}" id="nameForm">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="new_name" class="form-label">
                                    <i class="bi bi-pencil me-2"></i>新的用户姓名
                                </label>
                                <input type="text" 
                                       class="form-control @error('new_name') is-invalid @enderror" 
                                       id="new_name" 
                                       name="new_name" 
                                       value="{{ old('new_name', $user->uname) }}"
                                       required
                                       minlength="2"
                                       maxlength="50"
                                       placeholder="请输入新的用户姓名（2-50个字符）"
                                       oninput="validateName(this.value)">
                                @error('new_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="name-change-hint mt-1" id="nameHint">
                                    姓名长度2-50个字符，支持中文、英文、数字和常用符号
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6 mb-2">
                                    <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                        <i class="bi bi-check-circle me-2"></i>确认修改
                                    </button>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <a href="{{ route('user.info') }}" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-arrow-left me-2"></i>返回用户信息
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>注意事项：</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>用户姓名将在系统中所有显示您姓名的地方更新</li>
                                        <li>姓名修改后需要重新登录才能在所有地方生效</li>
                                        <li>建议使用真实姓名或常用昵称</li>
                                        <li>姓名修改不会影响您的用户ID和密码</li>
                                        <li>如果姓名中包含特殊字符，请确保其在系统中能正常显示</li>
                                    </ul>
                                </div>
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
    
    <script>
        function validateName(name) {
            const hint = document.getElementById('nameHint');
            const submitBtn = document.getElementById('submitBtn');
            
            if (name.length === 0) {
                hint.textContent = '姓名长度2-50个字符，支持中文、英文、数字和常用符号';
                hint.style.color = '#6c757d';
                submitBtn.disabled = true;
                return;
            }
            
            if (name.length < 2) {
                hint.textContent = '姓名太短，长度至少需要2个字符';
                hint.style.color = '#dc3545';
                submitBtn.disabled = true;
                return;
            }
            
            if (name.length > 50) {
                hint.textContent = '姓名太长，长度不能超过50个字符';
                hint.style.color = '#dc3545';
                submitBtn.disabled = true;
                return;
            }
            
            // 检查是否只包含合法字符
            const validPattern = /^[\u4e00-\u9fa5a-zA-Z0-9\s\-_\.·]+$/;
            if (!validPattern.test(name)) {
                hint.textContent = '姓名包含无效字符，请使用中文、英文、数字、空格、横线、下划线、点号或中间点';
                hint.style.color = '#dc3545';
                submitBtn.disabled = true;
                return;
            }
            
            // 检查是否与当前姓名相同
            const currentName = "{{ $user->uname }}";
            if (name === currentName) {
                hint.textContent = '新姓名与当前姓名相同，无需修改';
                hint.style.color = '#ffc107';
                submitBtn.disabled = true;
                return;
            }
            
            hint.textContent = '姓名格式正确 ✓';
            hint.style.color = '#28a745';
            submitBtn.disabled = false;
        }

        // 页面加载时验证姓名
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('new_name');
            validateName(nameInput.value);
            
            // 表单提交验证
            document.getElementById('nameForm').addEventListener('submit', function(e) {
                const name = document.getElementById('new_name').value;
                
                if (name.length < 2 || name.length > 50) {
                    e.preventDefault();
                    alert('姓名长度必须在2-50个字符之间');
                    return;
                }
                
                const currentName = "{{ $user->uname }}";
                if (name === currentName) {
                    e.preventDefault();
                    alert('新姓名与当前姓名相同，无需修改');
                    return;
                }
                
                // 显示加载状态
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>正在处理...';
            });
        });
    </script>
</body>
</html>