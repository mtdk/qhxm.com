<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/db/db.php';

$uid = trim($_POST['uid'] ? htmlspecialchars($_POST['uid']) : '');
$upassword = trim($_POST['upassword'] ? htmlspecialchars($_POST['upassword']) : '');
$remember_me = trim(htmlspecialchars($_POST['remember_me']));    // 用户是否选择记住我

if ($uid == '' || $upassword == '') {
    $_SESSION['msg'] = '用户姓名和密码不能为空，3秒后跳转回登录页面';
    $_SESSION['url'] = 'login.php';
    header('location:msgPage.php');
    die();
}

$stmt = $dbh->prepare("select uid,uname,upassword,organi_id,organi_name from usersinfo_view where uid=:uid");
$stmt->bindValue(':uid', $uid);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (count($result) == 0) {
    $_SESSION['msg'] = '用户信息不存在，3秒后跳转回登录页面';
    $_SESSION['url'] = 'login.php';
} elseif (!password_verify($upassword, $result['upassword'])) {
    $_SESSION['msg'] = '用户密码错误，3秒后跳转回登录页面';
    $_SESSION['url'] = 'login.php';
} else {
    $_SESSION['uid'] = $result['uid'];
    $_SESSION['uname'] = $result['uname'];
    $_SESSION['uorg_id'] = $result['organi_id'];
    $_SESSION['uorg_name']=$result['organi_name'];
    if ($remember_me == '0') {
        setcookie("remeber_myid", $uid, time() + 3600 * 24);
        setcookie("uspasswd", $upassword, time() + 3600 * 24);
    } else {
        setcookie("remeber_myid", '');
        setcookie("uspasswd", '');
    }
    $_SESSION['msg'] = '登录成功，3秒后跳转到系统首页';
    $_SESSION['url'] = 'index.php';
}
header('location:msgPage.php');
