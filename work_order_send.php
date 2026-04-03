<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';

$pro_id = $_POST['pro_id'];

$sql = "SELECT id,pro_id,bath_number,remarks,work_state,technology_target FROM work_order where pro_id = ? and work_state = 0;";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $pro_id);
$stmt->execute();
while (($result = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
    echo json_encode($result, JSON_UNESCAPED_UNICODE) . ' ';
}
