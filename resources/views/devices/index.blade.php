<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QHXM管理系统 - 设备管理</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .device-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 0.75rem;
            border: 1px solid rgba(0,0,0,0.125);
        }
        .device-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .device-type-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
        }
        .running-device {
            border-left: 4px solid #198754;
        }
        .stopped-device {
            border-left: 4px solid #dc3545;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house"></i> 首页
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('devices.index') }}">
                            <i class="bi bi-device-hdd"></i> 设备管理
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('work-orders.index') }}">
                            <i class="bi bi-clipboard-check"></i> 工单管理
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-tools"></i> 维修管理
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ session('uname', '用户') }}
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- 页面标题 -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-device-hdd"></i> 设备管理系统
            </h1>
            <div>
                <span class="badge bg-primary fs-6">
                    <i class="bi bi-clock"></i> {{ date('Y-m-d H:i:s') }}
                </span>
            </div>
        </div>

        <!-- 统计卡片 -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="stats-card">
                    <div class="row">
                        @php
                            $totalRunning = 0;
                            $totalDevices = 0;
                            foreach($deviceStats as $stat) {
                                $totalRunning += $stat['running'];
                                $totalDevices += $stat['total'];
                            }
                            $runningRate = $totalDevices > 0 ? round(($totalRunning / $totalDevices) * 100, 1) : 0;
                        @endphp
                        <div class="col-md-3 text-center">
                            <div class="stats-number">{{ $totalDevices }}</div>
                            <div class="stats-label">设备总数</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="stats-number">{{ $totalRunning }}</div>
                            <div class="stats-label">运行中设备</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="stats-number">{{ $totalDevices - $totalRunning }}</div>
                            <div class="stats-label">停止中设备</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="stats-number">{{ $runningRate }}%</div>
                            <div class="stats-label">设备运行率</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 设备类型卡片 -->
        <div class="row">
            @foreach($deviceTypes as $code => $name)
                @php
                    $stats = $deviceStats[$code] ?? ['total' => 0, 'running' => 0];
                    $runningRate = $stats['total'] > 0 ? round(($stats['running'] / $stats['total']) * 100, 1) : 0;
                    $iconClass = match($code) {
                        'FS' => 'bi-cpu',
                        'YM' => 'bi-gear-wide',
                        'KY' => 'bi-fan',
                        'BS' => 'bi-snow',
                        'FQ' => 'bi-cloud',
                        default => 'bi-question-circle'
                    };
                    $bgColor = match($code) {
                        'FS' => 'bg-primary',
                        'YM' => 'bg-success', 
                        'KY' => 'bg-info',
                        'BS' => 'bg-warning',
                        'FQ' => 'bg-secondary',
                        default => 'bg-light'
                    };
                @endphp
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card device-card h-100 {{ $stats['running'] > 0 ? 'running-device' : 'stopped-device' }}">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle {{ $bgColor }} text-white p-3">
                                        <i class="bi {{ $iconClass }} fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-0">{{ $name }}</h5>
                                    <p class="card-text text-muted small mb-0">
                                        设备编码: {{ $code }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6 text-center">
                                    <div class="text-primary fw-bold fs-4">{{ $stats['total'] }}</div>
                                    <div class="text-muted small">总设备数</div>
                                </div>
                                <div class="col-6 text-center">
                                    <div class="text-success fw-bold fs-4">{{ $stats['running'] }}</div>
                                    <div class="text-muted small">运行中</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">运行率</span>
                                    <span class="small fw-bold">{{ $runningRate }}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $runningRate }}%"></div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('devices.show', ['type' => $code]) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-list-ul"></i> 查看设备列表
                                </a>
                                <button type="button" class="btn btn-outline-secondary btn-sm" 
                                        onclick="searchDevices('{{ $code }}')">
                                    <i class="bi bi-search"></i> 搜索可用设备
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 设备状态说明 -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> 设备状态说明</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2">T</span>
                                    <span>运行中 (正常)</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-danger me-2">F</span>
                                    <span>停止中 (故障/维修)</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning me-2">M</span>
                                    <span>维护中</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-2">O</span>
                                    <span>其他状态</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 搜索设备模态框 -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">可用设备搜索</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">设备类型</label>
                        <select class="form-select" id="deviceTypeSelect">
                            @foreach($deviceTypes as $code => $name)
                                <option value="{{ $code }}">{{ $name }} ({{ $code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="performSearch()">
                        <i class="bi bi-search"></i> 搜索可用设备
                    </button>
                    <hr>
                    <div id="searchResults" class="mt-3" style="display: none;">
                        <h6>搜索结果：</h6>
                        <div id="devicesList" class="list-group"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // 搜索设备
        function searchDevices(type) {
            document.getElementById('deviceTypeSelect').value = type;
            const modal = new bootstrap.Modal(document.getElementById('searchModal'));
            modal.show();
            performSearch();
        }
        
        function performSearch() {
            const type = document.getElementById('deviceTypeSelect').value;
            const resultsDiv = document.getElementById('searchResults');
            const devicesList = document.getElementById('devicesList');
            
            // 显示加载中
            devicesList.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">加载中...</span>
                    </div>
                    <p class="mt-2">正在搜索可用设备...</p>
                </div>
            `;
            resultsDiv.style.display = 'block';
            
            // 发送AJAX请求
            fetch(`{{ route('devices.search') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ option: type })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    devicesList.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> ${data.error}
                        </div>
                    `;
                    return;
                }
                
                if (data.length === 0) {
                    devicesList.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle"></i> 没有找到可用的设备。
                        </div>
                    `;
                    return;
                }
                
                let html = '';
                data.forEach(device => {
                    html += `
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${device.machine_name}</h6>
                                    <small class="text-muted">设备编号: ${device.machine_id}</small>
                                </div>
                                <span class="badge bg-success">可用</span>
                            </div>
                        </div>
                    `;
                });
                
                devicesList.innerHTML = html;
            })
            .catch(error => {
                devicesList.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> 搜索失败: ${error.message}
                    </div>
                `;
            });
        }
        
        // 页面加载时检查用户信息
        document.addEventListener('DOMContentLoaded', function() {
            // 自动刷新页面数据（可选）
            // setInterval(() => {
            //     // 可以添加自动刷新逻辑
            // }, 60000); // 每分钟刷新一次
        });
    </script>
</body>
</html>