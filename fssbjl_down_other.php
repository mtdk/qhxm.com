<?php
$sql = "update fssbrecords set shutdown_time=:shutdown_time,total_duration=:total_duration,machine_status=:machine_status where id=:id";
$sth = $dbh->prepare($sql);
$sth->bindParam(':shutdown_time', $shutdown_time);
$sth->bindParam(':total_duration', $total_duration);
$sth->bindParam(':machine_status', $machine_status);
$sth->bindParam(':id', $id);
$sth->execute();
$affectedRows = $sth->rowCount();
if ($affectedRows < 0) {
    $_SESSION['msg'] = "数据保存失败！";
} elseif ($affectedRows > 0) {
    $_SESSION['msg'] = "数据保存成功！";
} else {
    $_SESSION['msg'] = "没有受影响的数据！";
}