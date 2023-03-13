<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/userinfo_check.php';
include __DIR__ . '/../db/db.php';

$start_time = $_POST['start_time'];
$stop_time = $_POST['stop_time'];
$userid = $_POST['userid'];

$sql = "SELECT machine_id,register_date,register_time,shutdown_time,total_duration,pro_id,bath_number,machine_status,uname FROM fssbjl_show where register_date>=? and register_date<=? and uid=? order by register_date,register_time";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $start_time);
$stmt->bindValue(2, $stop_time);
$stmt->bindValue(3, $userid);
$stmt->execute();
while (($result = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
    echo json_encode($result, JSON_UNESCAPED_UNICODE) . ' ';
}
