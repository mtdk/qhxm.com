<?php
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->beginTransaction();
// 修改分散记录表设备状态为关机
$sql = "update fssbrecords set shutdown_time=:shutdown_time,total_duration=:total_duration,machine_status=:machine_status,technology_target='FS' where id=:id";
$sth = $dbh->prepare($sql);
$sth->bindParam(':shutdown_time', $shutdown_time);
$sth->bindParam(':total_duration', $total_duration);
$sth->bindParam(':machine_status', $machine_status);
$sth->bindParam(':id', $id);
$sth->execute();

if ($technology_target=="YM"){
    //  修改工单总表记录状态为 2，激活研磨流程
    $sth = $dbh->prepare("update work_order set work_state = 2 where id = :work_id");
    $sth->bindParam(':work_id', $work_id);
    $sth->execute();
}
try {
    $dbh->commit();
    $_SESSION['msg'] = "数据已提交";
} catch (PDOException $e) {
    $dbh->rollBack();
    $_SESSION['msg'] = "提交失败: " . $e->getMessage();
}


