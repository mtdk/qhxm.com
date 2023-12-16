<?php
include __DIR__ . "/../user_session/user_session.php";
include __DIR__ . "/../user_session/login_state.php";
include __DIR__ . "/../db/db.php";

$uid = trim($_POST['userid'] ? htmlspecialchars($_POST['userid']) : '');
$areainsp_id = trim($_POST['areainsp_id'] ? htmlspecialchars($_POST['areainsp_id']) : '');

if (empty($uid)) {
    $_SESSION['msg'] = '用户ID不能为空';
    $_SESSION['url'] = '/fire_inspection/user_area.php';
    header('location:../msgPage.php');
    die();
} elseif (empty($areainsp_id)) {
    $_SESSION['msg'] = '区域ID不能为空';
    $_SESSION['url'] = '/fire_inspection/user_area.php';
    header('location:../msgPage.php');
    die();
} else {
    $stmt = $dbh->prepare("insert into users_areainspection(uid,areainsp_id) values(?,?)");
    $stmt->bindValue(1, $uid);
    $stmt->bindValue(2, $areainsp_id);
    $is = $stmt->execute();
    if ($is) {
        $_SESSION['msg'] = '记录已保存';
    } else {
        $_SESSION['msg'] = '记录保存失败';
    }
    $_SESSION['url'] = '/fire_inspection/user_area.php';
    header('location:../msgPage.php');
}