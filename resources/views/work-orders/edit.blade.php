<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QHXM管理系统 - 编辑工单</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .form-header {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        .form-card {
            border-radius: 0.75rem;
            border: 1px solid rgba(0,0,0,0.125);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .required::after {
            content: " *";
            color: #dc3545;
        }
        .field-info {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        .btn-submit {
            padding: 0.75rem 2rem;
            font-weight: 600;
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
        <div class="form-container">
            <!-- 页面标题 -->
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-pencil"></i> 编辑工单 #{{ $workOrder->id }}
                    </h1>
                    <a href="{{ route('work-orders.show', $workOrder->id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> 返回详情
                    </a>
                </div>
                <p class="text-muted mt-2 mb-0">修改工单信息</p>
            </div>

            <!-- 表单 -->
            <div class="form-card p-4">
                <form method="POST" action="{{ route('work-orders.update', $workOrder->id) }}" id="workOrderForm">
                    @csrf
                    @method('PUT')

                    <!-- 消息提示 -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>表单填写有误：</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- 第一行：产品编号和批号 -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="pro_id" class="form-label required">产品编号</label>
                            <input type="text" 
                                   class="form-control @error('pro_id') is-invalid @enderror" 
                                   id="pro_id" 
                                   name="pro_id" 
                                   value="{{ old('pro_id', $workOrder->pro_id) }}" 
                                   maxlength="20" 
                                   required
                                   placeholder="请输入产品编号">
                            @error('pro_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="field-info">最大20个字符</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bath_number" class="form-label required">批号</label>
                            <input type="text" 
                                   class="form-control @error('bath_number') is-invalid @enderror" 
                                   id="bath_number" 
                                   name="bath_number" 
                                   value="{{ old('bath_number', $workOrder->bath_number) }}" 
                                   maxlength="20" 
                                   required>
                            @error('bath_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="field-info">工单批号，不可重复</div>
                        </div>
                    </div>

                    <!-- 第二行：工艺选择和备注 -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="technology_target" class="form-label required">工艺选择</label>
                            <select class="form-select @error('technology_target') is-invalid @enderror" 
                                    id="technology_target" 
                                    name="technology_target" 
                                    required>
                                <option value="">请选择工艺类型</option>
                                <option value="FS" {{ old('technology_target', $workOrder->technology_target) == 'FS' ? 'selected' : '' }}>分散工艺 (FS)</option>
                                <option value="YM" {{ old('technology_target', $workOrder->technology_target) == 'YM' ? 'selected' : '' }}>研磨工艺 (YM)</option>
                            </select>
                            @error('technology_target')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="field-info">选择生产工艺类型</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="remarks" class="form-label required">备注</label>
                            <input type="text" 
                                   class="form-control @error('remarks') is-invalid @enderror" 
                                   id="remarks" 
                                   name="remarks" 
                                   value="{{ old('remarks', $workOrder->remarks) }}" 
                                   maxlength="20" 
                                   required
                                   placeholder="请输入客户名称或其他备注">
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="field-info">最大20个字符，例如客户名称</div>
                        </div>
                    </div>

                    <!-- 预览区域 -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-eye"></i> 工单预览</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <small class="text-muted">完整批号：</small>
                                        <div id="previewBatchNumber" class="fw-bold">{{ $workOrder->bath_number }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <small class="text-muted">工艺类型：</small>
                                        <div id="previewTechnology" class="fw-bold">{{ $workOrder->technology_target_text }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <small class="text-muted">工单状态：</small>
                                        <div>
                                            @php
                                                $statusColors = [
                                                    0 => 'secondary',
                                                    1 => 'primary',
                                                    2 => 'warning',
                                                    3 => 'success'
                                                ];
                                                $statusColor = $statusColors[$workOrder->work_state] ?? 'light';
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">{{ $workOrder->work_state_text }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <small class="text-muted">创建时间：</small>
                                        <div>{{ $workOrder->created_at ? $workOrder->created_at->format('Y-m-d H:i') : 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 表单按钮 -->
                    <div class="d-flex justify-content-between pt-3 border-top">
                        <div>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="bi bi-arrow-clockwise"></i> 重置表单
                            </button>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('work-orders.show', $workOrder->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> 取消
                            </a>
                            <button type="submit" class="btn btn-primary btn-submit">
                                <i class="bi bi-check-circle"></i> 更新工单
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 表单说明 -->
            <div class="mt-4">
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="bi bi-info-circle"></i> 编辑说明</h6>
                    <ul class="mb-0">
                        <li>批号不可与其他工单重复</li>
                        <li>工艺类型会影响设备运行记录</li>
                        <li>工单状态不能直接在此修改，需通过“发送工单”操作</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // 实时更新预览
        function updatePreview() {
            const batchNumber = document.getElementById('bath_number').value;
            const technology = document.getElementById('technology_target').value;
            
            // 更新完整批号预览
            document.getElementById('previewBatchNumber').textContent = batchNumber;
            
            // 更新工艺类型预览
            const technologyText = technology === 'FS' ? '分散工艺' : 
                                 technology === 'YM' ? '研磨工艺' : '未选择';
            document.getElementById('previewTechnology').textContent = technologyText;
        }
        
        // 重置表单
        function resetForm() {
            if (confirm('确定要重置表单吗？所有修改将被撤销。')) {
                // 重置为原始值（通过重新加载页面？）
                location.reload();
            }
        }
        
        // 表单验证
        document.getElementById('workOrderForm').addEventListener('submit', function(e) {
            const technology = document.getElementById('technology_target').value;
            
            // 工艺类型必须选择
            if (!technology) {
                e.preventDefault();
                alert('请选择工艺类型！');
                document.getElementById('technology_target').focus();
                return false;
            }
            
            // 确认提交
            if (!confirm('确定要更新工单信息吗？')) {
                e.preventDefault();
                return false;
            }
        });
        
        // 监听输入变化
        document.getElementById('bath_number').addEventListener('input', updatePreview);
        document.getElementById('technology_target').addEventListener('change', updatePreview);
        
        // 页面加载时初始化
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
        });
    </script>
</body>
</html>