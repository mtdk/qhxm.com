<?php
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->beginTransaction();
$sth = $dbh->prepare("insert into fssbrecords (machine_id,register_date,register_time,pro_id,bath_number,machine_status,uid,work_id,technology_target) values(:machine_id,:register_date,:register_time,:pro_id,:bath_number,:machine_status,:uid,:work_id,:technology_target)");
$sth->bindParam(':machine_id', $machine_id);
$sth->bindParam(':register_date', $register_date);
$sth->bindParam(':register_time', $register_time);
$sth->bindParam(':pro_id', $pro_id);
$sth->bindParam(':bath_number', $bath_number);
$sth->bindParam(':machine_status', $machine_status);
$sth->bindParam(':uid', $uid);
$sth->bindParam(':work_id', $work_id);
$sth->bindParam('technology_target', $technology_target);
$sth->execute();
$sth = $dbh->prepare("update work_order set work_state = :work_state where id = :work_id");
$sth->bindParam(':work_state', $work_state);
$sth->bindParam(':work_id', $work_id);
$sth->execute();
try {
    $dbh->commit();
    $_SESSION['msg'] = "数据已提交";
} catch (PDOException $e) {
    $dbh->rollBack();
    $_SESSION['msg'] = "提交失败: " . $e->getMessage();
}