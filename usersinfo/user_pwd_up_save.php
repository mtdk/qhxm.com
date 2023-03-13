<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/login_state.php';
include __DIR__ . '/../db/db.php';

$userid = trim($_POST['userid'] ? htmlspecialchars($_POST['userid']) : '');
$old_passwd = trim($_POST['old_passwd'] ? htmlspecialchars($_POST['old_passwd']) : '');
$new_passwd = trim($_POST['new_passwd'] ? htmlspecialchars($_POST['new_passwd']) : '');
$replay_passwd = trim($_POST['replay_passwd'] ? htmlspecialchars($_POST['replay_passwd']) : '');

if (empty($userid)) {
  $_SESSION['msg'] = '用户ID不能为空';
  $_SESSION['url'] = 'user_pwd_up.php';
  header('location:../msgPage.php');
  die();
} elseif (empty($old_passwd)) {
  $_SESSION['msg'] = '用户原始密码不能为空';
  $_SESSION['url'] = 'user_pwd_up.php';
  header('location:../msgPage.php');
  die();
} elseif (empty($new_passwd)) {
  $_SESSION['msg'] = '用户新密码不能为空';
  $_SESSION['url'] = 'user_pwd_up.php';
  header('location:../msgPage.php');
  die();
} elseif (empty($replay_passwd)) {
  $_SESSION['msg'] = '用户确认密码不能为空';
  $_SESSION['url'] = 'user_pwd_up.php';
  header('location:../msgPage.php');
  die();
} elseif ($replay_passwd != $new_passwd) {
  $_SESSION['msg'] = '两次密码不一致';
  $_SESSION['url'] = 'user_pwd_up.php';
  header('location:../msgPage.php');
  die();
} else {
  $stmt = $dbh->prepare("select uid,upassword from usertb where uid=:userid");
  $stmt->bindParam(':userid', $userid);
  $stmt->execute();
  $rs = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!password_verify($old_passwd, $rs['upassword'])) {
    $_SESSION['msg'] = '您的原始密码不正确！';
    $_SESSION['url'] = 'user_pwd_up.php';
    header('location:../msgPage.php');
    die();
  } else {
    $upassword = password_hash($new_passwd, PASSWORD_DEFAULT);
    $stmt = $dbh->prepare("update usertb set upassword=:upassword where uid=:userid");
    $stmt->bindParam(':upassword', $upassword);
    $stmt->bindParam(':userid', $userid);
    $is = $stmt->execute();
    if ($is) {
      $_SESSION['msg'] = '用户密码修改完成，请重新登录。';
      $_SESSION['url'] = 'logout.php';
    } else {
      $_SESSION['msg'] = '数据写入失败';
      $_SESSION['url'] = 'user_pwd_up.php';
    }
    header('location:../msgPage.php');
  }
}