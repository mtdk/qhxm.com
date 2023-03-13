<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/login_state.php';
include __DIR__ . '/../db/db.php';

$id = trim($_POST['id'] ? htmlspecialchars($_POST['id']) : '');
$old_organi_id = trim($_POST['old_organi_id'] ? htmlspecialchars($_POST['old_organi_id']) : '');
$new_organi_id = trim($_POST['new_organi_id'] ? htmlspecialchars($_POST['new_organi_id']) : '');

if (empty($uid)) {
  $_SESSION['msg'] = '用户ID不能为空';
  $_SESSION['url'] = '/usersinfo/user_organization.php';
  header('location:../msgPage.php');
  die();
} elseif (empty($old_organi_id)) {
  $_SESSION['msg'] = '关键ID不能为空';
  $_SESSION['url'] = '/usersinfo/user_organization.php';
  header('location:../msgPage.php');
  die();
} elseif ($old_organi_id == 1000) {
  $stmt = $dbh->prepare("select count(*) as result from usersorganization where organi_id=1000");
  $stmt->execute();
  $rs = $stmt->fetchAll()[0]['result'];
  if ($rs == 1) {
    $_SESSION['msg'] = '至少需要保留一个管理员账号';
    $_SESSION['url'] = '/usersinfo/user_organization.php';
    header('location:../msgPage.php');
    die();
  }
} elseif (empty($new_organi_id)) {
  $_SESSION['msg'] = '组织ID不能为空';
  $_SESSION['url'] = '/usersinfo/user_organization.php';
  header('location:../msgPage.php');
  die();
}
$stmt = $dbh->prepare("update usersorganization set organi_id=:organi_id where uid=:id");
$stmt->bindParam(':organi_id', $new_organi_id, PDO::PARAM_INT);
$stmt->bindParam(':id', $id);
$is = $stmt->execute();
if ($is) {
  $_SESSION['msg'] = '用户组织修改完成';
} else {
  $_SESSION['msg'] = '数据写入失败';
}
$_SESSION['url'] = '/usersinfo/user_organization.php';
header('location:../msgPage.php');