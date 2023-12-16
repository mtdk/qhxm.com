<?php
if (!isset($_SESSION['admin_uid'])) {
    $_SESSION['msg'] = '您还未登录系统，请先登录系统，3秒后跳转回登录页面';
    $_SESSION['url'] = 'admin_login.php';
    header('location:/Admin_folder/admin_msgPage.php');
    exit;
}