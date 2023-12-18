<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';

$id = trim($_POST['id'] ? htmlspecialchars($_POST['id']) : '');
$shutdown_time = trim($_POST['shutdown_time'] ? htmlspecialchars($_POST['shutdown_time']) : '');
$total_duration = trim($_POST['total_duration'] ? htmlspecialchars($_POST['total_duration']) : '');
$work_state = trim($_POST['work_state'] ? htmlspecialchars($_POST['work_state']) : 0);
$machine_status = "关机";
if (empty($id)) {
    $_SESSION['msg'] = "关键ID错误";
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($shutdown_time)) {
    $_SESSION['msg'] = "设备关闭时间不能为空";
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($total_duration)) {
    $_SESSION['msg'] = "总时长不能为空";
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
    die();
} else {
    $stmt = $dbh->prepare("SELECT id,machine_status from fssbrecords where id=:id and machine_status='关机'");
    $stmt->bindParam('id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rs = $stmt->fetchAll();
    if (count($rs) > 0) {
        $_SESSION['msg'] = $uname . "你好！请不要重复提交操作！";
        $_SESSION['url'] = 'fssbjl.php';
        header('location:msgPage.php');
        die();
    }
    if ($work_state == 0) {
        include_once __DIR__ . '/fssbjl_down_auto.php';
    } else {
        include_once __DIR__ . '/fssbjl_down_other.php';
    }
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
}
