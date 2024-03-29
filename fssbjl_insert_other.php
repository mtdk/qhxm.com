<?php
$sql = "insert into fssbrecords (machine_id,register_date,register_time,pro_id,bath_number,machine_status,uid,work_id,technology_target) values(:machine_id,:register_date,:register_time,:pro_id,:bath_number,:machine_status,:uid,:work_id,:technology_target)";
$sth = $dbh->prepare($sql);
$sth->bindParam(':machine_id', $machine_id);
$sth->bindParam(':register_date', $register_date);
$sth->bindParam(':register_time', $register_time);
$sth->bindParam(':pro_id', $pro_id);
$sth->bindParam(':bath_number', $bath_number);
$sth->bindParam(':machine_status', $machine_status);
$sth->bindParam(':uid', $uid);
$sth->bindParam(':work_id',$work_id);
$sth->bindParam(':technology_target',$technology_target);
$sth->execute();
$affectedRows = $sth->rowCount();
if ($affectedRows < 0) {
    $_SESSION['msg'] = "数据保存失败！";
} elseif ($affectedRows > 0) {
    $_SESSION['msg'] = "数据保存成功！";
} else {
    $_SESSION['msg'] = "没有受影响的数据！";
}