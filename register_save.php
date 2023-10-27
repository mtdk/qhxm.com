<?php
include __DIR__ . '/db/db.php';

$uid = trim((htmlspecialchars(($_POST['userid']))) ?? '');
$uname = trim(htmlspecialchars($_POST['username']) ?? '');
$upasswd = trim(htmlspecialchars($_POST['userpwd']) ?? '');
$reupasswd = trim(htmlspecialchars($_POST['reuserpwd']) ?? '');

if ($uid == '') {
    header('refresh:3;url=register.php');
    echo '用户ID不能为空，3秒后跳转回注册页面';
} elseif ($uname == '') {
    header('refresh:3;url=register.php');
    echo '用户名不能为空，3秒后跳转回注册页面';
} elseif ($upasswd == '') {
    header('refresh:3;url=register.php');
    echo '用户密码不能为空，3秒后跳转回注册页面';
} elseif ($reupasswd == '') {
    header('refresh:3;url=register.php');
    echo '确认密码不能为空，3秒后跳转回注册页面';
} elseif ($upasswd != $reupasswd) {
    header('refresh:3;url=register.php');
    echo '两次密码不一致，3秒后跳转回注册页面';
}

$stmt = $dbh->prepare("select count(*) as total from usertb where uid=:uid");
$stmt->execute(array(':uid' => $uid));
$result = $stmt->fetchAll();
if ($result[0]['total'] > 0) {
    header('refresh:1;url=register.php');
    echo '用户已存在';
}

$upasswd = password_hash($upasswd, PASSWORD_DEFAULT);

$stmt = $dbh->prepare("INSERT INTO usertb (uid,uname,upassword) VALUES (:uid,:uname,:upassword)");
$stmt->bindParam(':uid', $uid);
$stmt->bindParam(':uname', $uname);
$stmt->bindParam('upassword', $upasswd);
$is = $stmt->execute();
if ($is) {
    header('refresh:3;url=register.php');
    echo '用户注册成功';
} else {
    die('数据写入失败');
}