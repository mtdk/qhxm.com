<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QHXM管理系统 - 密码修改</title>
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
        .password-change-card {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .password-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        .password-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .password-weak {
            background-color: #dc3545;
            width: 25%;
        }
        .password-medium {
            background-color: #ffc107;
            width: 50%;
        }
        .password-strong {
            background-color: #28a745;
            width: 75%;
        }
        .password-very-strong {
            background-color: #20c997;
            width: 100%;
        }
        .password-hint {
            font-size: 0.875rem;
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
                                    <a class="nav-link dropdown-toggle {{ request()->routeIs('user.password.change') && $menu['label'] === '用户中心' ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                                        <i class="{{ $menu['icon'] }}"></i> {{ $menu['label'] }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach($menu['items'] as $submenu)
                                            <li><a class="dropdown-item {{ request()->routeIs('user.password.change') && $submenu['label'] === '密码修改' ? 'active' : '' }}" href="{{ $submenu['url'] }}"><i class="{{ $submenu['icon'] }}"></i> {{ $submenu['label'] }}</a></li>
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
                                <li><a class="dropdown-item" href="{{ route('user.name.change') }}"><i class="bi bi-pencil-square"></i> 用户姓名修改</a></li>
                                <li><a class="dropdown-item active" href="{{ route('user.password.change') }}"><i class="bi bi-key"></i> 密码修改</a></li>
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
            <i class="bi bi-key"></i> 修改密码
        </h3>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card password-change-card">
                    <div class="password-header">
                        <div class="password-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h4>安全密码修改</h4>
                        <p class="mb-0">请填写以下信息修改您的登录密码</p>
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

                        <form method="POST" action="{{ route('user.password.update') }}" id="passwordForm">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="current_password" class="form-label">
                                    <i class="bi bi-lock me-2"></i>当前密码
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required
                                           autocomplete="current-password">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('current_password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @error('current_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="new_password" class="form-label">
                                    <i class="bi bi-key me-2"></i>新密码
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('new_password') is-invalid @enderror" 
                                           id="new_password" 
                                           name="new_password" 
                                           required
                                           minlength="6"
                                           autocomplete="new-password"
                                           oninput="checkPasswordStrength(this.value)">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('new_password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @error('new_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="password-strength" id="passwordStrength"></div>
                                <div class="password-hint mt-1" id="passwordHint">
                                    密码长度至少6个字符，建议包含字母、数字和特殊字符
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label">
                                    <i class="bi bi-key-fill me-2"></i>确认新密码
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="new_password_confirmation" 
                                           name="new_password_confirmation" 
                                           required
                                           minlength="6"
                                           autocomplete="new-password"
                                           oninput="checkPasswordMatch()">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('new_password_confirmation')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="password-hint mt-1" id="passwordMatchHint">
                                    请再次输入新密码以确认
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
                                    <strong>安全提示：</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>请使用强密码，包含大小写字母、数字和特殊字符</li>
                                        <li>不要使用与其他网站相同的密码</li>
                                        <li>定期更换密码以提高账户安全性</li>
                                        <li>不要将密码告诉他人或在公共场所输入密码</li>
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
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('passwordStrength');
            const hintText = document.getElementById('passwordHint');
            
            // 重置
            strengthBar.className = 'password-strength';
            
            if (password.length === 0) {
                hintText.textContent = '密码长度至少6个字符，建议包含字母、数字和特殊字符';
                hintText.style.color = '#6c757d';
                return;
            }
            
            if (password.length < 6) {
                strengthBar.className = 'password-strength password-weak';
                hintText.textContent = '密码太短，长度至少需要6个字符';
                hintText.style.color = '#dc3545';
                return;
            }
            
            // 评估密码强度
            let strength = 0;
            
            // 长度加分
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // 包含小写字母
            if (/[a-z]/.test(password)) strength += 1;
            
            // 包含大写字母
            if (/[A-Z]/.test(password)) strength += 1;
            
            // 包含数字
            if (/[0-9]/.test(password)) strength += 1;
            
            // 包含特殊字符
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // 设置强度显示
            let strengthClass = '';
            let strengthText = '';
            let textColor = '';
            
            if (strength <= 2) {
                strengthClass = 'password-weak';
                strengthText = '密码强度：弱';
                textColor = '#dc3545';
            } else if (strength <= 4) {
                strengthClass = 'password-medium';
                strengthText = '密码强度：中等';
                textColor = '#ffc107';
            } else if (strength <= 5) {
                strengthClass = 'password-strong';
                strengthText = '密码强度：强';
                textColor = '#28a745';
            } else {
                strengthClass = 'password-very-strong';
                strengthText = '密码强度：非常强';
                textColor = '#20c997';
            }
            
            strengthBar.className = 'password-strength ' + strengthClass;
            hintText.textContent = strengthText;
            hintText.style.color = textColor;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            const hint = document.getElementById('passwordMatchHint');
            
            if (confirmPassword.length === 0) {
                hint.textContent = '请再次输入新密码以确认';
                hint.style.color = '#6c757d';
                return;
            }
            
            if (password === confirmPassword) {
                hint.textContent = '密码匹配 ✓';
                hint.style.color = '#28a745';
            } else {
                hint.textContent = '密码不匹配 ✗';
                hint.style.color = '#dc3545';
            }
        }

        // 表单提交验证
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            
            if (password.length < 6) {
                e.preventDefault();
                alert('密码长度至少需要6个字符');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('两次输入的密码不一致，请重新输入');
                return;
            }
            
            // 显示加载状态
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>正在处理...';
        });
    </script>
</body>
</html>