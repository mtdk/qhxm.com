<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QHXM管理系统 - 首页</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .device-count-badge {
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
        }
        .progress {
            height: 1.5rem;
        }
        .main-container {
            margin-top: 2rem;
        }
        .welcome-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .section-title {
            color: #34495e;
            border-left: 4px solid #3498db;
            padding-left: 1rem;
            margin: 2rem 0 1rem 0;
        }
    </style>
</head>
<body>
    <!-- 公共菜单 -->
    @include('components.menu')

    <!-- 主要内容 -->
    <main class="container main-container">
        <h1 class="welcome-title">
            <i class="bi bi-house-check"></i> 欢迎登录本系统
        </h1>

        <!-- 设备运行状态 -->
        <h3 class="section-title">
            <i class="bi bi-play-circle"></i> 当前设备运行状态
        </h3>
        <div class="row mb-4">
            @foreach($deviceCounts as $deviceType => $count)
                @if($count > 0)
                    <div class="col-md-4 mb-2">
                        <span class="badge text-bg-warning device-count-badge w-100">
                            <i class="bi bi-exclamation-triangle"></i> 
                            当前有 {{ $count }} 台{{ $deviceType }}正在运行。
                        </span>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- 工单状态 -->
        <h3 class="section-title">
            <i class="bi bi-clipboard"></i> 工单状态
        </h3>
        <div class="row mb-4">
            @foreach($workOrderCounts as $orderType => $count)
                @if($count > 0)
                    <div class="col-md-6 mb-2">
                        <span class="badge text-bg-danger device-count-badge w-100">
                            <i class="bi bi-exclamation-circle"></i>
                            当前有 {{ $count }} 张{{ $orderType }}未领取。
                        </span>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- 维修进度 -->
        @if($repairOrders->count() > 0)
            <h3 class="section-title">
                <i class="bi bi-tools"></i> 设备维修进度
            </h3>
            <div class="row mb-4">
                @foreach($repairOrders as $repair)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-wrench"></i> 
                                    {{ $repair->device_id }} 维修进度
                                    <span class="badge bg-{{ $repair->repair_status_color }} float-end">
                                        {{ $repair->repair_status_text }}
                                    </span>
                                </h5>
                                <p class="card-text">
                                    <i class="bi bi-chat-left-text"></i> {{ $repair->repair_msg }}
                                </p>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         style="width: {{ $repair->progress }}%">
                                        {{ $repair->progress }}%
                                    </div>
                                </div>
                                <div class="mt-2 text-muted small">
                                    <i class="bi bi-calendar"></i> 申请时间: {{ $repair->apply_time }}
                                    @if($repair->auditor_name)
                                        | <i class="bi bi-person-check"></i> 审核人: {{ $repair->auditor_name }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- 系统统计信息 -->
        <h3 class="section-title">
            <i class="bi bi-bar-chart"></i> 系统统计
        </h3>
        <div class="row mb-4" id="stats-container">
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h1 class="display-6 text-primary">
                            <i class="bi bi-hdd-stack"></i>
                            <span id="total-devices">--</span>
                        </h1>
                        <p class="card-text">总设备数</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h1 class="display-6 text-success">
                            <i class="bi bi-play-circle"></i>
                            <span id="running-devices">--</span>
                        </h1>
                        <p class="card-text">运行中设备</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h1 class="display-6 text-warning">
                            <i class="bi bi-clipboard"></i>
                            <span id="unclaimed-orders">--</span>
                        </h1>
                        <p class="card-text">未领取工单</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h1 class="display-6 text-danger">
                            <i class="bi bi-tools"></i>
                            <span id="active-repairs">--</span>
                        </h1>
                        <p class="card-text">进行中维修</p>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // 加载统计信息
            function loadStats() {
                $.ajax({
                    url: '{{ route("dashboard") }}',
                    method: 'GET',
                    success: function(data) {
                        $('#total-devices').text(data.total_devices.value);
                        $('#running-devices').text(data.running_devices.value);
                        $('#unclaimed-orders').text(data.unclaimed_orders.value);
                        $('#active-repairs').text(data.active_repairs.value);
                    },
                    error: function() {
                        console.error('无法加载统计信息');
                    }
                });
            }

            // 页面加载时获取统计信息
            loadStats();

            // 每30秒刷新一次统计信息
            setInterval(loadStats, 30000);

            // 实时状态更新
            function updateRealtimeStatus() {
                $.ajax({
                    url: '{{ route("realtime-status") }}',
                    method: 'GET',
                    success: function(data) {
                        // 可以在这里更新实时状态显示
                        console.log('实时状态更新:', data);
                    },
                    error: function() {
                        console.error('无法更新实时状态');
                    }
                });
            }

            // 每60秒更新一次实时状态
            setInterval(updateRealtimeStatus, 60000);
        });
    </script>
</body>
</html>