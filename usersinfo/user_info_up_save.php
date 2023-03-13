<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/login_state.php';
include __DIR__ . '/../db/db.php';

$userid = trim($_POST['userid'] ? htmlspecialchars($_POST['userid']) : '');
$uname = trim($_POST['uname'] ? htmlspecialchars($_POST['uname']) : '');

if (empty($userid)){
  $_SESSION['msg']='用户ID不能为空';
  $_SESSION['url']='/usersinfo/user_info_up.php';
  header('location:../msgPage.php');
  die();
}elseif(empty($uname)){
  $_SESSION['msg']='用户姓名不能为空';
  $_SESSION['url']='/usersinfo/user_info_up.php';
  header('location:../msgPage.php');
  die();
}else{
  $stmt = $dbh->prepare("update usertb set uname=:uname where uid=:userid");
  $stmt->bindParam(':uname', $uname);
  $stmt->bindParam(':userid', $userid);
  $is = $stmt->execute();
  if ($is){
    $_SESSION['msg']='用户姓名修改完成,请重新登录';
    $_SESSION['url']='logout.php';
  }else{
    $_SESSION['msg']='数据写入失败';
    $_SESSION['url']='/usersinfo/user_info_up.php';
  }
  header('location:../msgPage.php');
}

