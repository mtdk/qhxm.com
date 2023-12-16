<?php
include __DIR__ . '/../db/db.php';
session_start();
$_SESSION['url'] = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $uid = trim(base64_decode(htmlspecialchars($_GET['uid'])));
    $new_user_pwd = 'abc123456';       // 用户的原始密码
    $user_pwd = password_hash($new_user_pwd, PASSWORD_DEFAULT);
    if (empty($uid)) {
        $_SESSION['msg'] = "用户ID不能为空";
    } else {
        // 提交数据
        $sth = $dbh->prepare("update usertb set upassword = :new_user_pwd where uid = :uid");
        $sth->bindParam('new_user_pwd', $user_pwd);
        $sth->bindParam('uid', $uid);
        $sth->execute();
        $affectedRows = $sth->rowCount();

        if ($affectedRows < 0) {
            $_SESSION['msg'] = "密码重置失败了！";
        } elseif ($affectedRows > 0) {
            $_SESSION['msg'] = "密码已重置，当前密码是：" . $new_user_pwd;
        } else {
            $_SESSION['msg'] = "已是原始密码，无需重置！";
        }
    }
    $_SESSION['url'] = 'user_info_query.php';
} else {
    // err_msg
    $_SESSION['msg'] = "错误的提交方式，危险的操作，将自动退出登录！！！";
    $_SESSION['url'] = 'admin_logout.php';
}
header('location:admin_msgPage.php');
die();