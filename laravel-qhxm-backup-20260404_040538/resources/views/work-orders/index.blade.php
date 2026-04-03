<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QHXM管理系统 - 工单管理</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .workorder-card {
            transition: transform 0.2s;
            border-radius: 0.5rem;
        }
        .workorder-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .pagination .page-link {
            border-radius: 0.375rem;
        }
        .stats-card {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
        }
        .stats-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        .state-0 { border-left: 4px solid #6c757d; }
        .state-1 { border-left: 4px solid #0d6efd; }
        .state-2 { border-left: 4px solid #fd7e14; }
        .state-3 { border-left: 4px solid #198754; }
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
                        <a class="nav-link" href="{{ route('devices.index') }}">
                            <i class="bi bi-device-hdd"></i> 设备管理
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('work-orders.index') }}">
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
        <!-- 页面标题和操作按钮 -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-clipboard-check"></i> 工单管理系统
            </h1>
            <div>
                <a href="{{ route('work-orders.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> 创建新工单
                </a>
                <button class="btn btn-outline-secondary" onclick="refreshData()">
                    <i class="bi bi-arrow-clockwise"></i> 刷新
                </button>
            </div>
        </div>

        <!-- 统计卡片 -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="stats-card">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="stats-number">{{ $workOrders->total() }}</div>
                            <div class="stats-label">总工单数</div>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <div class="stats-number">{{ $workOrders->where('work_state', 0)->count() }}</div>
                            <div class="stats-label">未领取</div>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <div class="stats-number">{{ $workOrders->where('work_state', 1)->count() }}</div>
                            <div class="stats-label">已领取</div>
                        </div>
                        <div class="col-md-2 mb-3 mb-md-0">
                            <div class="stats-number">{{ $workOrders->where('work_state', 2)->count() }}</div>
                            <div class="stats-label">进行中</div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-number">{{ $workOrders->where('work_state', 3)->count() }}</div>
                            <div class="stats-label">已完成</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 消息提示 -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- 工单列表 -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> 工单列表</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="80">ID</th>
                                <th>产品编号</th>
                                <th>批号</th>
                                <th>工艺类型</th>
                                <th>备注</th>
                                <th>状态</th>
                                <th width="150">创建时间</th>
                                <th width="180">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($workOrders as $order)
                                <tr class="workorder-card state-{{ $order->work_state }}">
                                    <td class="fw-bold">#{{ $order->id }}</td>
                                    <td>{{ $order->pro_id }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $order->bath_number }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $order->technology_target == 'FS' ? 'bg-primary' : 'bg-warning' }}">
                                            {{ $order->technology_target_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($order->remarks, 15) }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                0 => 'secondary',
                                                1 => 'primary',
                                                2 => 'warning',
                                                3 => 'success'
                                            ];
                                            $statusColor = $statusColors[$order->work_state] ?? 'light';
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }} status-badge">
                                            {{ $order->work_state_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $order->created_at ? $order->created_at->format('m-d H:i') : 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="action-buttons d-flex gap-2">
                                            <a href="{{ route('work-orders.show', $order->id) }}" 
                                               class="btn btn-sm btn-outline-info" title="查看详情">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('work-orders.edit', $order->id) }}" 
                                               class="btn btn-sm btn-outline-warning" title="编辑">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($order->work_state == 0)
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="sendWorkOrder({{ $order->id }})" title="发送工单">
                                                    <i class="bi bi-send"></i>
                                                </button>
                                            @endif
                                            <form method="POST" action="{{ route('work-orders.destroy', $order->id) }}" 
                                                  class="d-inline" onsubmit="return confirm('确定要删除这个工单吗？')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="删除">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                            <p class="mt-2">暂无工单数据</p>
                                            <a href="{{ route('work-orders.create') }}" class="btn btn-primary btn-sm mt-2">
                                                <i class="bi bi-plus-circle"></i> 创建第一个工单
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($workOrders->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            显示 {{ $workOrders->firstItem() }} 到 {{ $workOrders->lastItem() }} 条，共 {{ $workOrders->total() }} 条
                        </div>
                        <nav>
                            {{ $workOrders->links() }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>

        <!-- 工单状态说明 -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> 工单状态说明</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-2">未领取</span>
                                    <small>等待操作员领取</small>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-2">已领取</span>
                                    <small>操作员已领取</small>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning me-2">进行中</span>
                                    <small>正在生产中</small>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2">已完成</span>
                                    <small>生产已完成</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bi bi-tags"></i> 工艺类型说明</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-2">FS</span>
                                    <small>分散工艺</small>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning me-2">YM</span>
                                    <small>研磨工艺</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // 发送工单
        function sendWorkOrder(orderId) {
            if (!confirm('确定要发送这个工单吗？')) {
                return;
            }
            
            fetch(`/work-orders/${orderId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('工单发送成功！');
                    location.reload();
                } else {
                    alert('发送失败：' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('发送失败，请稍后重试');
            });
        }
        
        // 刷新数据
        function refreshData() {
            location.reload();
        }
        
        // 页面加载时初始化
        document.addEventListener('DOMContentLoaded', function() {
            // 自动刷新数据（可选）
            // setInterval(refreshData, 60000); // 每分钟刷新一次
            
            // 添加键盘快捷键
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'n') {
                    e.preventDefault();
                    window.location.href = '{{ route("work-orders.create") }}';
                }
            });
        });
    </script>
</body>
</html>