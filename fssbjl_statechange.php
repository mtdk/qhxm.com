<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/db/db.php';
if ($uid == '') {
    header('refresh:3;url=login.php');
    die('您还未登录系统，请先登录系统，3秒后跳转回登录页面');
}

$id = trim($_GET['id'] ? htmlspecialchars($_GET['id']) : '');
$send_uid = trim($_GET['uid'] ? htmlspecialchars($_GET['uid']) : '');
if ($id == '' || $send_uid == '') {
    header('refresh:3;url=fssbjl.php');
    die("<h2>参数传递错误，请与管理员联系，3秒后跳转回登录页面</h2>");
}
if ($uid != $send_uid) {
    header('refresh:3;url=fssbjl.php');
    die("<h2>此设备不是你开启的，你不能进行关闭操作，3秒后跳转回登录页面</h2>");
}

$stmt = $dbh->prepare("SELECT id,mechine_id,register_date,register_time,pro_id,bath_number from fssbrecords where id=:id");
$stmt->bindParam('id', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo $result['mechine_id'];

