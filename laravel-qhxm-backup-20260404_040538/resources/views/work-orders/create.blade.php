<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QHXM管理系统 - 创建工单</title>
    
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
                        <i class="bi bi-plus-circle"></i> 创建新工单
                    </h1>
                    <a href="{{ route('work-orders.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> 返回列表
                    </a>
                </div>
                <p class="text-muted mt-2 mb-0">填写以下信息创建新的生产工单</p>
            </div>

            <!-- 表单 -->
            <div class="form-card p-4">
                <form method="POST" action="{{ route('work-orders.store') }}" id="workOrderForm">
                    @csrf

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
                                   value="{{ old('pro_id') }}" 
                                   maxlength="20" 
                                   required
                                   placeholder="请输入产品编号">
                            @error('pro_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="field-info">最大20个字符，例如：PROD-2023-001</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bath_number" class="form-label required">批号</label>
                            <input type="text" 
                                   class="form-control @error('bath_number') is-invalid @enderror" 
                                   id="bath_number" 
                                   name="bath_number" 
                                   value="{{ old('bath_number', $today) }}" 
                                   maxlength="20" 
                                   required
                                   readonly>
                            @error('bath_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="field-info">自动生成：YYYYMMDD格式 (今日: {{ $today }})</div>
                        </div>
                    </div>

                    <!-- 第二行：批次序号和批次数 -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="bath_number_index" class="form-label required">批次序号</label>
                            <input type="text" 
                                   class="form-control @error('bath_number_index') is-invalid @enderror" 
                                   id="bath_number_index" 
                                   name="bath_number_index" 
                                   value="{{ old('bath_number_index') }}" 
                                   minlength="3" 
                                   maxlength="3" 
                                   required
                                   placeholder="例如：001">
                            @error('bath_number_index')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="field-info">必须为3位数字，例如：001、002、123</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lot_number" class="form-label">批次数</label>
                            <input type="number" 
                                   class="form-control @error('lot_number') is-invalid @enderror" 
                                   id="lot_number" 
                                   name="lot_number" 
                                   value="{{ old('lot_number', 1) }}" 
                                   min="1" 
                                   max="10">
                            @error('lot_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="field-info">创建相同工单的数量 (1-10)</div>
                        </div>
                    </div>

                    <!-- 第三行：工艺选择和备注 -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="technology_target" class="form-label required">工艺选择</label>
                            <select class="form-select @error('technology_target') is-invalid @enderror" 
                                    id="technology_target" 
                                    name="technology_target" 
                                    required>
                                <option value="">请选择工艺类型</option>
                                <option value="FS" {{ old('technology_target') == 'FS' ? 'selected' : '' }}>分散工艺 (FS)</option>
                                <option value="YM" {{ old('technology_target') == 'YM' ? 'selected' : '' }}>研磨工艺 (YM)</option>
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
                                   value="{{ old('remarks') }}" 
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
                                        <div id="previewBatchNumber" class="fw-bold">{{ $defaultBatchNumber }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <small class="text-muted">工艺类型：</small>
                                        <div id="previewTechnology" class="fw-bold">未选择</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <small class="text-muted">创建数量：</small>
                                        <div id="previewLotNumber" class="fw-bold">1</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">
                                        <small class="text-muted">工单状态：</small>
                                        <div><span class="badge bg-secondary">未领取</span></div>
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
                            <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> 取消
                            </a>
                            <button type="submit" class="btn btn-primary btn-submit">
                                <i class="bi bi-check-circle"></i> 创建工单
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 表单说明 -->
            <div class="mt-4">
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="bi bi-info-circle"></i> 创建说明</h6>
                    <ul class="mb-0">
                        <li>批号 = 日期 ({{ $today }}) + 批次序号 (例如: {{ $today }}001)</li>
                        <li>创建多个相同工单时，系统会自动生成连续的批号</li>
                        <li>工单创建后状态为"未领取"，需要操作员手动领取</li>
                        <li>工艺类型决定了后续的设备选择范围</li>
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
            const batchIndex = document.getElementById('bath_number_index').value;
            const technology = document.getElementById('technology_target').value;
            const lotNumber = document.getElementById('lot_number').value;
            
            // 更新完整批号预览
            const fullBatchNumber = batchNumber + batchIndex;
            document.getElementById('previewBatchNumber').textContent = fullBatchNumber;
            
            // 更新工艺类型预览
            const technologyText = technology === 'FS' ? '分散工艺' : 
                                 technology === 'YM' ? '研磨工艺' : '未选择';
            document.getElementById('previewTechnology').textContent = technologyText;
            
            // 更新创建数量预览
            document.getElementById('previewLotNumber').textContent = lotNumber || '1';
        }
        
        // 重置表单
        function resetForm() {
            if (confirm('确定要重置表单吗？所有输入的内容将被清除。')) {
                document.getElementById('workOrderForm').reset();
                updatePreview();
            }
        }
        
        // 表单验证
        document.getElementById('workOrderForm').addEventListener('submit', function(e) {
            const batchIndex = document.getElementById('bath_number_index').value;
            const technology = document.getElementById('technology_target').value;
            
            // 批次序号必须是3位数字
            if (!/^\d{3}$/.test(batchIndex)) {
                e.preventDefault();
                alert('批次序号必须是3位数字！');
                document.getElementById('bath_number_index').focus();
                return false;
            }
            
            // 工艺类型必须选择
            if (!technology) {
                e.preventDefault();
                alert('请选择工艺类型！');
                document.getElementById('technology_target').focus();
                return false;
            }
            
            // 确认提交
            const lotNumber = document.getElementById('lot_number').value || 1;
            if (lotNumber > 1) {
                const confirmMsg = `您将创建 ${lotNumber} 个工单，确认提交吗？`;
                if (!confirm(confirmMsg)) {
                    e.preventDefault();
                    return false;
                }
            }
        });
        
        // 监听输入变化
        document.getElementById('bath_number_index').addEventListener('input', updatePreview);
        document.getElementById('technology_target').addEventListener('change', updatePreview);
        document.getElementById('lot_number').addEventListener('input', updatePreview);
        
        // 页面加载时初始化
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
            
            // 自动填充产品编号（如果之前有输入过）
            const lastProId = localStorage.getItem('lastProId');
            if (lastProId && !document.getElementById('pro_id').value) {
                document.getElementById('pro_id').value = lastProId;
            }
            
            // 保存产品编号到本地存储
            document.getElementById('pro_id').addEventListener('blur', function() {
                if (this.value) {
                    localStorage.setItem('lastProId', this.value);
                }
            });
        });
    </script>
</body>
</html>