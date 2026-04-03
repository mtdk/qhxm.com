<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QHXM管理系统 - 用户登录</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .form-signin {
            max-width: 330px;
            padding: 1rem;
        }
        .form-signin .form-floating:focus-within {
            z-index: 2;
        }
        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .remember-check {
            margin-top: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .form-floating {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <h1 class="h3 mb-3 fw-normal">
                    <i class="bi bi-gear-fill"></i> QHXM管理系统
                </h1>
                <p class="text-muted">请登录您的账户</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-floating">
                    <select class="form-select @error('uid') is-invalid @enderror" 
                            id="uid" 
                            name="uid" 
                            onchange="checkRememberedUser(this.value)"
                            required autofocus>
                        <option value="">请选择用户...</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->uid }}" 
                                    @if($user->uid == old('uid')) selected @endif
                                    @if($user->uid == $rememberedUid) selected @endif>
                                {{ $user->uname }} ({{ $user->uid }})
                            </option>
                        @endforeach
                    </select>
                    <label for="uid">
                        <i class="bi bi-person-circle"></i> 选择用户
                    </label>
                    @error('uid')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating">
                    <input type="password" 
                           class="form-control @error('upassword') is-invalid @enderror" 
                           id="upassword" 
                           name="upassword" 
                           placeholder="密码" 
                           required>
                    <label for="upassword">
                        <i class="bi bi-key"></i> 密码
                    </label>
                    @error('upassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check remember-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="remember_me" 
                           id="remember_me" 
                           value="1"
                           @if(old('remember_me') || $rememberedUid) checked @endif>
                    <label class="form-check-label" for="remember_me">
                        <i class="bi bi-bookmark-check"></i> 记住我
                    </label>
                </div>

                <button class="btn btn-primary w-100 py-2 mb-3" type="submit">
                    <i class="bi bi-box-arrow-in-right"></i> 登录
                </button>

                <div class="text-center">
                    <a href="#" class="text-decoration-none">
                        <i class="bi bi-question-circle"></i> 忘记密码？
                    </a>
                </div>
            </form>

            <hr class="my-4">

            <div class="text-center text-muted small">
                <i class="bi bi-shield-check"></i> 安全登录系统 &copy; {{ date('Y') }}
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <script>
        function checkRememberedUser(uid) {
            if (!uid) return;
            
            // 获取CSRF令牌的多种方式
            let csrfToken = '';
            // 方式1: 从meta标签获取
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                csrfToken = csrfMeta.content;
            }
            // 方式2: 从表单中的_token字段获取
            if (!csrfToken) {
                const tokenInput = document.querySelector('input[name="_token"]');
                if (tokenInput) {
                    csrfToken = tokenInput.value;
                }
            }
            // 方式3: 从XSRF-TOKEN cookie获取
            if (!csrfToken) {
                const xsrfCookie = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='));
                if (xsrfCookie) {
                    csrfToken = decodeURIComponent(xsrfCookie.split('=')[1]);
                }
            }
            
            if (!csrfToken) {
                console.error('无法获取CSRF令牌');
                return;
            }
            
            console.log('检查记住的用户:', uid, 'CSRF令牌长度:', csrfToken.length);
            
            fetch('{{ route("auth.checkRemembered") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ uid: uid })
            })
            .then(response => {
                console.log('API响应状态:', response.status);
                if (!response.ok) {
                    throw new Error('API请求失败: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('API响应数据:', data);
                if (data.remembered && data.password) {
                    document.getElementById('upassword').value = data.password;
                    document.getElementById('remember_me').checked = true;
                    console.log('已填充记住的密码');
                } else {
                    document.getElementById('upassword').value = '';
                    document.getElementById('remember_me').checked = false;
                    console.log('没有记住的密码');
                }
            })
            .catch(error => {
                console.error('检查记住的用户失败:', error);
                // 静默失败，不影响正常登录
            });
        }

        // 页面加载时自动检查记住的用户
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM已加载，检查记住的用户...');
            const uidSelect = document.getElementById('uid');
            if (uidSelect && uidSelect.value) {
                // 延迟执行，确保所有元素都已加载
                setTimeout(() => checkRememberedUser(uidSelect.value), 100);
            }
        });

        // 处理下拉框变化事件
        document.addEventListener('change', function(event) {
            if (event.target && event.target.id === 'uid') {
                checkRememberedUser(event.target.value);
            }
        });
    </script>
</body>
</html>