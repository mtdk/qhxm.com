<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../db/db.php';

$uid = trim((htmlspecialchars(($_POST['userid']))) ?? '');
$uname = trim(htmlspecialchars($_POST['username']) ?? '');
$upasswd = trim(htmlspecialchars($_POST['userpwd']) ?? '');
$reupasswd = trim(htmlspecialchars($_POST['reuserpwd']) ?? '');

if ($uid == '') {
  $_SESSION['msg'] = '用户ID不能为空，3秒后跳转回注册页面';
  $_SESSION['url'] = '/usersinfo/register.php';
  header('location:../msgPage.php');
  die();
} elseif ($uname == '') {
  $_SESSION['msg'] = '用户名不能为空，3秒后跳转回注册页面';
  $_SESSION['url'] = '/usersinfo/register.php';
  header('location:../msgPage.php');
  die();
} elseif ($upasswd == '') {
  $_SESSION['msg'] = '用户密码不能为空，3秒后跳转回注册页面';
  $_SESSION['url'] = '/usersinfo/register.php';
  header('location:../msgPage.php');
  die();
} elseif ($reupasswd == '') {
  $_SESSION['msg'] = '确认密码不能为空，3秒后跳转回注册页面';
  $_SESSION['url'] = '/usersinfo/register.php';
  header('location:../msgPage.php');
  die();
} elseif ($upasswd != $reupasswd) {
  $_SESSION['msg'] = '两次密码不一致，3秒后跳转回注册页面';
  $_SESSION['url'] = '/usersinfo/register.php';
  header('location:../msgPage.php');
  die();
}


$stmt = $dbh->prepare("select count(*) as total from usertb where uid=:uid");
$stmt->execute(array(':uid' => $uid));
$result = $stmt->fetchAll();
if ($result[0]['total'] > 0) {
  $_SESSION['msg'] = '用户已存在';
  $_SESSION['url'] = '/usersinfo/register.php';
  header('location:../msgPage.php');
  die();
}
$upasswd = password_hash($upasswd, PASSWORD_DEFAULT);
try {
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->beginTransaction();
  $stmt = $dbh->prepare("INSERT INTO usertb (uid,uname,upassword) VALUES (:uid,:uname,:upassword)");
  $stmt->execute(array(':uid' => $uid, ':uname' => $uname, ':upassword' => $upasswd));
  $stmt = $dbh->prepare("insert into usersorganization(uid,organi_id) values(:uid,:organi_id)");
  $stmt->execute(array(':uid' => $uid, ':organi_id' => 100));
  $is = $dbh->commit();
  if ($is) {
    $_SESSION['msg'] = '用户注册成功';
    $_SESSION['url'] = 'login.php';
    header('location:../msgPage.php');
  }
} catch (Exception $e) {
  $dbh->rollBack();
  echo "数据写入失败，" . $e->getMessage();
}
