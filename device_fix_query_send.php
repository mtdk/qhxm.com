<?php
include_once __DIR__ . '/user_session/user_session.php';
include_once __DIR__ . '/user_session/login_state.php';
include_once __DIR__ . '/db/db.php';

// 获取开始时间和结束时间
$start_time = $_POST['start_time'];
$stop_time = $_POST['stop_time'];

// 查询数据库
$stmt = $dbh->prepare("SELECT repair_id,device_name,repairer_name FROM tb_device_repair_order WHERE manager_check_time BETWEEN ? AND ? AND repair_status = 4");
$stmt->bindValue(1, $start_time);
$stmt->bindValue(2, $stop_time);
$stmt->execute();
while (($results = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
    echo json_encode($results, JSON_UNESCAPED_UNICODE) . ' ';
}


// 关闭数据库连接
$pdo = null;

// 输出查询结果
// foreach ($results as $row) {
//     echo $row['id'] . ' ' . $row['device_id'] .