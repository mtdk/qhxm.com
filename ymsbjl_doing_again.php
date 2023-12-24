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

// 复制原记录信息，新增工单总表记录，记录状态默认为 0，激活分散供应流程，进入再次生产领用
$sth = $dbh->prepare("insert into work_order (select null,pro_id,bath_number,remarks,work_state=1,technology_target from work_order where id = :work_id)");
$sth->bindParam(':work_id', $work_id);
$sth->execute();

//  修改工单总表记录状态为 2，激活研磨流程
$sth = $dbh->prepare("update work_order set work_state = 2 where id = :work_id");
$sth->bindParam(':work_id', $work_id);
$sth->execute();

try {
    $dbh->commit();
    $_SESSION['msg'] = "数据已提交";
} catch (PDOException $e) {
    $dbh->rollBack();
    $_SESSION['msg'] = "提交失败: " . $e->getMessage();
}


