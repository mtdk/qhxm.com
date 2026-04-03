<?php
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->beginTransaction();
// 修改研磨记录表设备状态为关机
$sql = "update ymsbrecords set shutdown_time=:shutdown_time,total_duration=:total_duration,machine_status=:machine_status where id=:id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':shutdown_time', $shutdown_time);
$stmt->bindParam(':total_duration', $total_duration);
$stmt->bindParam(':machine_status', $machine_status);
$stmt->bindParam(':id', $id);
$is = $stmt->execute();
try {
    $dbh->commit();
    $_SESSION['msg'] = "数据已提交";
} catch (PDOException $e) {
    $dbh->rollBack();
    $_SESSION['msg'] = "提交失败: " . $e->getMessage();
}


