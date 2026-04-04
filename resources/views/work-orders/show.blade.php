<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QHXM管理系统 - 工单详情</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .detail-card {
            border-radius: 1rem;
            border-left: 6px solid #0d6efd;
        }
        .state-0 { border-left-color: #6c757d; }
        .state-1 { border-left-color: #0d6efd; }
        .state-2 { border-left-color: #fd7e14; }
        .state-3 { border-left-color: #198754; }
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
    <!-- 公共菜单 -->
    @include('components.menu')

    <div class="container mt-4">
        <!-- 页面标题和操作按钮 -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h1 class="h3 mb-3 mb-md-0 flex-shrink-0">
                <i class="bi bi-clipboard-check"></i> 工单详情
            </h1>
            <div class="d-flex flex-wrap gap-2 justify-content-end w-100 w-md-auto">
                <a href="{{ route('work-orders.index') }}" class="btn btn-outline-secondary flex-fill flex-md-grow-0">
                    <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">返回列表</span>
                    <span class="d-inline d-md-none">返回</span>
                </a>
                <a href="{{ route('work-orders.edit', $workOrder->id) }}" class="btn btn-warning flex-fill flex-md-grow-0">
                    <i class="bi bi-pencil"></i> <span class="d-none d-md-inline">编辑</span>
                    <span class="d-inline d-md-none">编辑</span>
                </a>
                <form method="POST" action="{{ route('work-orders.destroy', $workOrder->id) }}" class="d-inline flex-fill flex-md-grow-0" onsubmit="return confirm('确定要删除这个工单吗？')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> <span class="d-none d-md-inline">删除</span>
                        <span class="d-inline d-md-none">删除</span>
                    </button>
                </form>
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

        <!-- 工单详情卡片 -->
        <div class="card detail-card state-{{ $workOrder->work_state }}">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle"></i> 基本信息
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="120">工单ID：</th>
                                <td>#{{ $workOrder->id }}</td>
                            </tr>
                            <tr>
                                <th>产品编号：</th>
                                <td>{{ $workOrder->pro_id }}</td>
                            </tr>
                            <tr>
                                <th>批号：</th>
                                <td>
                                    <span class="badge bg-info text-dark badge-lg">{{ $workOrder->bath_number }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>工艺类型：</th>
                                <td>
                                    <span class="badge {{ $workOrder->technology_target == 'FS' ? 'bg-primary' : 'bg-warning' }}">
                                        {{ $workOrder->technology_target_text }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="120">工单状态：</th>
                                <td>
                                    @php
                                        $statusColors = [
                                            0 => 'secondary',
                                            1 => 'primary',
                                            2 => 'warning',
                                            3 => 'success'
                                        ];
                                        $statusColor = $statusColors[$workOrder->work_state] ?? 'light';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }} badge-lg">
                                        {{ $workOrder->work_state_text }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>备注：</th>
                                <td>{{ $workOrder->remarks }}</td>
                            </tr>
                            <tr>
                                <th>创建时间：</th>
                                <td>{{ $workOrder->created_at ? $workOrder->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // 页面特定的JavaScript代码可以放在这里
        // 目前没有特定的功能需要实现
    </script>
</body>
</html>