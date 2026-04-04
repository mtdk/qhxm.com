@extends('layouts.app')

@section('title', 'fqsb记录打印 - QHXM管理系统')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-printer me-2"></i>fqsb记录打印
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi                         <i class="bi                         <i class="bi                         <i class="bi                         <i class="bi                         <i class="bi                         <i>
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>序号</th>
                                    <th>设备名称</th>
                                    <th>设备状态</th>
                                    <th>记录时间</th>
                                    <th>操作员</th>
                                    <th>备注</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $index => $record)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $record->device_name ?? '分散设备' }}</td>
                                    <td>
                                        @if(($record->machine_status ?? '') == '开机')
                                        <span class="badge bg-success">开机</span>
                                        @else
                                        <span class="badge bg-secondary">关机</span>
                                        @endif
                                    </td>
                                    <td>{{ $record->create_time ?? date('Y-m-d H:i:s') }}</td>
                                    <td>{{ $record->operator ?? '系统' }}</td>
                                    <td>{{ $record->remark ?? '无' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                        暂无记录数据
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="bi bi-printer me-2"></i>打印当前页
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-arrow-left me-2"></i>返回首页
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
