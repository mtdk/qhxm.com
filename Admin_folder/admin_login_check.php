<?php
include __DIR__ . '/admin_sessions/admin_session.php';
include __DIR__ . '/../db/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_uid = trim(htmlspecialchars(strip_tags($_POST['admin_uid'])) ?? '');
    $admin_pwd = trim(htmlspecialchars(strip_tags($_POST['admin_pwd'])) ?? '');
    if ($admin_uid == '' || $admin_pwd == '') {
        $_SESSION['msg'] = '用户姓名和密码不能为空，3秒后跳转回登录页面';
        $_SESSION['url'] = '/Admin_folder/admin_login.php';
        header('location:admin_msgPage.php');
        die();
    }

    $stmt = $dbh->prepare("select admin_id,admin_pwd from tb_admin where admin_id=:admin_uid");
    $stmt->bindValue(':admin_uid', $admin_uid);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) == 0) {
        $_SESSION['msg'] = '用户信息不存在，3秒后跳转回登录页面';
        $_SESSION['url'] = '/Admin_folder/admin_login.php';
    } elseif (!password_verify($admin_pwd, $result[0]['admin_pwd'])) {
        $_SESSION['msg'] = '密码错误，3秒后跳转回登录页面';
        $_SESSION['url'] = '/Admin_folder/admin_login.php';
    } else {
        $_SESSION['admin_uid'] = $admin_uid;
        $_SESSION['admin_name'] = $result[0]['admin_name'];
        $_SESSION['msg'] = '登录成功，3秒后跳转到系统首页';
        $_SESSION['url'] = '/Admin_folder/admin_index.php';
    }
} else {
    $_SESSION['msg'] = '错误的提交方式，危险的操作！！！3秒后跳转回登录页面';
    $_SESSION['url'] = '/Admin_folder/admin_login.php';
}
header('location:admin_msgPage.php');