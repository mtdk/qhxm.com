<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ValidateModelConsistency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'models:validate 
                            {--model= : 指定要验证的模型名称}
                            {--fix : 自动修复不一致的字段}
                            {--dry-run : 只显示修复内容，不实际执行}
                            {--export : 导出数据库结构到文件}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '验证Laravel模型与数据库字段的一致性';

    /**
     * 模型与表名映射关系
     */
    protected $modelTableMap = [
        'UserTb' => 'usertb',
        'WorkOrder' => 'work_order',
        'Department' => 'tb_departments',
        'Role' => 'tb_role',
        'UserState' => 'tb_userstate',
        'DeviceRepairOrder' => 'tb_device_repair_order',
        'Ymsb' => 'ymsb',
        'Fssb' => 'fssb',
        'Bsjsb' => 'bsjsb',
        'Kyjsb' => 'kyjsb',
        'Fqsb' => 'fqsb',
        'YmsbRecord' => 'ymsbrecords',
        'FssbRecord' => 'fssbrecords',
        'BsjsbRecord' => 'bsjrecords',
        'KyjsbRecord' => 'kyjrecords',
        'FqsbRecord' => 'fqssrecords',
        'Device' => 'device',
        'DeviceRecord' => 'device_records',
        'User' => 'users',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('export')) {
            $this->exportDatabaseSchema();
            return;
        }

        $this->info('开始验证Laravel模型与数据库字段一致性...');
        $this->newLine();

        $models = $this->getModelsToValidate();

        $totalIssues = 0;
        $totalModels = count($models);

        foreach ($models as $modelName) {
            $issues = $this->validateModel($modelName);
            $totalIssues += $issues;
        }

        $this->newLine();
        
        if ($totalIssues === 0) {
            $this->info("✅ 所有 {$totalModels} 个模型验证通过，字段完全一致！");
        } else {
            $this->error("❌ 发现 {$totalIssues} 个不一致问题，需要修复。");
            $this->line("使用 --fix 选项自动修复，或手动修改模型文件。");
        }
    }

    /**
     * 获取要验证的模型列表
     */
    private function getModelsToValidate(): array
    {
        $specifiedModel = $this->option('model');
        
        if ($specifiedModel) {
            if (!isset($this->modelTableMap[$specifiedModel])) {
                $this->error("模型 {$specifiedModel} 未在映射表中定义");
                return [];
            }
            return [$specifiedModel];
        }

        // 获取所有模型
        $models = [];
        $modelPath = app_path('Models');
        
        if (!File::exists($modelPath)) {
            $this->error("模型目录不存在: {$modelPath}");
            return [];
        }

        $files = File::files($modelPath);
        
        foreach ($files as $file) {
            $modelName = $file->getFilenameWithoutExtension();
            if (isset($this->modelTableMap[$modelName])) {
                $models[] = $modelName;
            }
        }

        return $models;
    }

    /**
     * 验证单个模型
     */
    private function validateModel(string $modelName): int
    {
        $tableName = $this->modelTableMap[$modelName];
        
        $this->line("🔍 验证模型: <comment>{$modelName}</comment> -> 表: <comment>{$tableName}</comment>");

        // 获取模型字段
        $modelFields = $this->getModelFields($modelName);
        if ($modelFields === null) {
            $this->error("   无法获取模型 {$modelName} 的字段信息");
            return 1;
        }

        // 获取数据库字段
        $dbFields = $this->getDatabaseFields($tableName);
        if ($dbFields === null) {
            $this->error("   无法获取表 {$tableName} 的字段信息");
            return 1;
        }

        // 比较字段
        $issues = $this->compareFields($modelName, $modelFields, $dbFields);

        if ($issues === 0) {
            $this->info("   ✅ 字段完全一致 (" . count($modelFields) . " 个字段)");
        }

        return $issues;
    }

    /**
     * 获取模型字段
     */
    private function getModelFields(string $modelName): ?array
    {
        $modelClass = "App\\Models\\{$modelName}";
        
        if (!class_exists($modelClass)) {
            return null;
        }

        try {
            $reflection = new \ReflectionClass($modelClass);
            
            // 如果是抽象类，跳过验证
            if ($reflection->isAbstract()) {
                $this->info("   ⏭️  跳过抽象类: {$modelName}");
                return [];
            }
            
            $model = new $modelClass;
            $fillable = $model->getFillable();
            
            // 添加主键字段（如果不在fillable中）
            $primaryKey = $model->getKeyName();
            if (!in_array($primaryKey, $fillable)) {
                $fillable[] = $primaryKey;
            }

            // 添加时间戳字段（如果模型使用时间戳）
            if ($model->usesTimestamps()) {
                $fillable[] = $model->getCreatedAtColumn();
                $fillable[] = $model->getUpdatedAtColumn();
            }

            sort($fillable);
            return $fillable;
        } catch (\Exception $e) {
            $this->error("   获取模型字段失败: " . $e->getMessage());
            return null;
        }
    }

    /**
     * 获取数据库字段
     */
    private function getDatabaseFields(string $tableName): ?array
    {
        try {
            $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
            sort($columns);
            return $columns;
        } catch (\Exception $e) {
            // 尝试使用原始SQL查询
            try {
                $columns = DB::select("SHOW COLUMNS FROM {$tableName}");
                $fieldNames = array_column($columns, 'Field');
                sort($fieldNames);
                return $fieldNames;
            } catch (\Exception $e2) {
                $this->error("   获取数据库字段失败: " . $e2->getMessage());
                return null;
            }
        }
    }

    /**
     * 比较字段并报告差异
     */
    private function compareFields(string $modelName, array $modelFields, array $dbFields): int
    {
        $issues = 0;

        // 模型有但数据库没有的字段
        $modelOnly = array_diff($modelFields, $dbFields);
        if (!empty($modelOnly)) {
            $this->warn("   ⚠️  模型有但数据库没有的字段: " . implode(', ', $modelOnly));
            $issues += count($modelOnly);
            
            if ($this->option('fix')) {
                $this->fixModelFields($modelName, $modelFields, $dbFields);
            }
        }

        // 数据库有但模型没有的字段
        $dbOnly = array_diff($dbFields, $modelFields);
        if (!empty($dbOnly)) {
            $this->warn("   ⚠️  数据库有但模型没有的字段: " . implode(', ', $dbOnly));
            $issues += count($dbOnly);
            
            if ($this->option('fix')) {
                $this->fixModelFields($modelName, $modelFields, $dbFields);
            }
        }

        return $issues;
    }

    /**
     * 修复模型字段
     */
    private function fixModelFields(string $modelName, array $modelFields, array $dbFields): void
    {
        $modelClass = "App\\Models\\{$modelName}";
        $modelFile = app_path("Models/{$modelName}.php");
        
        if (!File::exists($modelFile)) {
            $this->error("   模型文件不存在: {$modelFile}");
            return;
        }

        $content = File::get($modelFile);
        
        // 移除模型有但数据库没有的字段
        $modelOnly = array_diff($modelFields, $dbFields);
        $dbOnly = array_diff($dbFields, $modelFields);
        
        // 构建新的fillable数组
        $newFillable = array_intersect($modelFields, $dbFields);
        $newFillable = array_merge($newFillable, $dbOnly);
        
        // 移除主键字段（通常不在fillable中）
        $model = new $modelClass;
        $primaryKey = $model->getKeyName();
        $newFillable = array_filter($newFillable, fn($field) => $field !== $primaryKey);
        
        // 移除时间戳字段（如果模型不使用时间戳）
        if (!$model->usesTimestamps()) {
            $newFillable = array_filter($newFillable, fn($field) => 
                $field !== $model->getCreatedAtColumn() && 
                $field !== $model->getUpdatedAtColumn()
            );
        }
        
        sort($newFillable);
        
        // 查找并替换fillable数组
        $pattern = '/protected\s+\$fillable\s*=\s*\[(.*?)\];/s';
        
        if (preg_match($pattern, $content, $matches)) {
            $oldFillable = $matches[1];
            $newFillableStr = "protected \$fillable = [\n        '" . implode("',\n        '", $newFillable) . "',\n    ];";
            
            $newContent = str_replace($matches[0], $newFillableStr, $content);
            
            if ($this->option('dry-run')) {
                $this->info("   📝 将修改 {$modelName} 的 \$fillable 为:");
                $this->line("      " . implode(', ', $newFillable));
            } else {
                File::put($modelFile, $newContent);
                $this->info("   ✅ 已修复 {$modelName} 的字段定义");
            }
        } else {
            $this->error("   在模型文件中找不到 \$fillable 定义");
        }
    }

    /**
     * 导出数据库结构
     */
    private function exportDatabaseSchema(): void
    {
        $this->info('导出数据库结构...');
        
        $tables = DB::select('SHOW TABLES');
        $databaseName = DB::getDatabaseName();
        
        $schema = [];
        
        foreach ($tables as $table) {
            $tableName = $table->{"Tables_in_{$databaseName}"};
            
            $columns = DB::select("SHOW COLUMNS FROM {$tableName}");
            $columnNames = array_column($columns, 'Field');
            
            $schema[$tableName] = $columnNames;
        }
        
        $exportFile = storage_path('logs/database_schema_' . date('Ymd_His') . '.json');
        File::put($exportFile, json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $this->info("✅ 数据库结构已导出到: {$exportFile}");
        $this->line("   包含 " . count($schema) . " 个表的结构信息");
    }
}