<?php
include __DIR__ . "/../user_session/user_session.php";
include __DIR__ . "/../user_session/login_state.php";
include __DIR__ . "/../db/db.php";

$uid = trim($_POST['uid'] ? htmlspecialchars($_POST['uid']) : '');
$organi_id = trim($_POST['organi_id'] ? htmlspecialchars($_POST['organi_id']) : '');

if (empty($uid)) {
    $_SESSION['msg'] = '用户ID不能为空';
    $_SESSION['url'] = '/usersinfo/user_organization.php';
    header('location:../msgPage.php');
    die();
} elseif (empty($organi_id)) {
    $_SESSION['msg'] = '组织ID不能为空';
    $_SESSION['url'] = '/usersinfo/user_organization.php';
    header('location:../msgPage.php');
    die();
} else {
    $stmt = $dbh->prepare("insert into usersorganization(uid,organi_id) values(?,?)");
    $stmt->bindValue(1, $uid);
    $stmt->bindValue(2, $organi_id);
    $is = $stmt->execute();
    if ($is) {
        $_SESSION['msg'] = '记录已保存';
    } else {
        $_SESSION['msg'] = '记录保存失败';
    }
    $_SESSION['url'] = '/usersinfo/user_organization.php';
    header('location:../msgPage.php');
}