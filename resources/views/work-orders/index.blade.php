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
        .pagination {
            margin-bottom: 0;
            --bs-pagination-padding-x: 0.75rem;
            --bs-pagination-padding-y: 0.375rem;
            --bs-pagination-font-size: 0.875rem;
            --bs-pagination-border-radius: 0.375rem;
        }
        .pagination .page-link {
            border-radius: var(--bs-pagination-border-radius);
            border: 1px solid #dee2e6;
            color: #495057;
            padding: var(--bs-pagination-padding-y) var(--bs-pagination-padding-x);
            margin: 0 0.125rem;
            font-size: var(--bs-pagination-font-size);
            line-height: 1.5;
            min-width: 2.5rem;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 2.25rem;
        }
        .pagination .page-link svg,
        .pagination .page-link i,
        .pagination .page-link .bi {
            width: 1rem !important;
            height: 1rem !important;
            font-size: 1rem !important;
            vertical-align: -0.125em;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
            font-weight: 500;
        }
        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #495057;
            text-decoration: none;
        }
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #f8f9fa;
            border-color: #dee2e6;
            opacity: 0.6;
        }
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            min-width: 2.5rem;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .pagination .page-item:first-child .page-link svg,
        .pagination .page-item:first-child .page-link i,
        .pagination .page-item:first-child .page-link .bi,
        .pagination .page-item:last-child .page-link svg,
        .pagination .page-item:last-child .page-link i,
        .pagination .page-item:last-child .page-link .bi {
            width: 1rem !important;
            height: 1rem !important;
            font-size: 1rem !important;
        }
        @media (max-width: 768px) {
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.25rem;
            }
            .pagination .page-link {
                padding: 0.25rem 0.5rem;
                font-size: 0.8125rem;
                min-width: 2.25rem;
                margin: 0;
                height: 2rem;
            }
            .pagination .page-item:first-child .page-link,
            .pagination .page-item:last-child .page-link {
                padding-left: 0.375rem;
                padding-right: 0.375rem;
                min-width: 2.25rem;
            }
            .card-footer .d-flex {
                flex-direction: column;
                gap: 1rem;
            }
            .card-footer .text-muted {
                text-align: center;
            }
        }
        .stats-card {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
        }
        .stats-number {
            font-size: 1.25rem;
            font-weight: 600;
            line-height: 1.1;
        }
        .stats-label {
            font-size: 0.7rem;
            opacity: 0.9;
            margin-top: 0.2rem;
        }
        .workorder-row {
            border-left: none;
            transition: all 0.2s ease;
        }
        .workorder-row:hover {
            background-color: #f8f9fa;
            transform: none;
        }
        .workorder-row:nth-child(even) {
            background-color: #fcfcfc;
        }
        .workorder-row:nth-child(even):hover {
            background-color: #f5f5f5;
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
                <i class="bi bi-clipboard-check"></i> 工单管理
            </h1>
            <div class="d-flex flex-wrap gap-2 justify-content-end w-100 w-md-auto">
                <a href="{{ route('work-orders.create') }}" class="btn btn-primary flex-fill flex-md-grow-0">
                    <i class="bi bi-plus-circle"></i> <span class="d-none d-md-inline">创建新工单</span>
                    <span class="d-inline d-md-none">新建</span>
                </a>
                <button class="btn btn-outline-secondary flex-fill flex-md-grow-0" onclick="refreshData()">
                    <i class="bi bi-arrow-clockwise"></i> <span class="d-none d-md-inline">刷新</span>
                    <span class="d-inline d-md-none">刷新</span>
                </button>
            </div>
        </div>

        <!-- 统计卡片 -->
        <div class="row mb-1">
            <div class="col-12">
                <div class="stats-card">
                    <div class="row g-2 text-center">
                        <div class="col-6 col-md-3">
                            <div class="stats-number">{{ $workOrders->total() }}</div>
                            <div class="stats-label">未领取工单</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stats-number">{{ $workOrders->count() }}</div>
                            <div class="stats-label">本页显示</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stats-number">{{ $workOrders->perPage() }}</div>
                            <div class="stats-label">每页显示</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stats-number">{{ $workOrders->currentPage() }}</div>
                            <div class="stats-label">当前页码</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 消息提示 -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
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
                                <tr class="workorder-row">
                                    <td class="fw-bold">
                                        <a href="{{ route('work-orders.show', $order->id) }}" 
                                           class="text-decoration-none text-primary" 
                                           title="点击查看工单详情">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
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
                            {{ $workOrders->links('vendor.pagination.simple-bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>

        <!-- 工艺类型说明 -->
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bi bi-tags"></i> 工艺类型说明</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-2">FS</span>
                                    <small>分散工艺</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
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