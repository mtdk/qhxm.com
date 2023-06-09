<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';

$id = trim($_POST['id'] ? htmlspecialchars($_POST['id']) : '');
$shutdown_time = trim($_POST['shutdown_time'] ? htmlspecialchars($_POST['shutdown_time']) : '');
$total_duration = trim($_POST['total_duration'] ? htmlspecialchars($_POST['total_duration']) : '');
$machine_status = trim($_POST['radio_stacked'] ? htmlspecialchars($_POST['radio_stacked']) : '');
if (empty($id)) {
    $_SESSION['msg'] = "关键ID错误";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($shutdown_time)) {
    $_SESSION['msg'] = "设备关闭时间不能为空";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($total_duration)) {
    $_SESSION['msg'] = "总时长不能为空";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($machine_status)) {
    $_SESSION['msg'] = "设备当前状态不能为空";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
}else{
    $stmt = $dbh->prepare("SELECT id,machine_status from ymsbrecords where id=:id and machine_status='关机'");
    $stmt->bindParam('id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rs = $stmt->fetchAll();
    if (count($rs) > 0) {
        $_SESSION['msg'] = $uname . "你好！请不要重复提交操作！";
        $_SESSION['url'] = 'ymsbjl.php';
        header('location:msgPage.php');
        die();
    }
    $sql = "update ymsbrecords set shutdown_time=:shutdown_time,total_duration=:total_duration,machine_status=:machine_status where id=:id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':shutdown_time', $shutdown_time);
    $stmt->bindParam(':total_duration', $total_duration);
    $stmt->bindParam(':machine_status', $machine_status);
    $stmt->bindParam(':id', $id);
    $is = $stmt->execute();

    if ($is) {
        $_SESSION['msg'] = "数据已提交";
    } else {
        $_SESSION['msg'] = "数据写入失败，请与管理联系，3秒后返回录入页面";
    }
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
}
