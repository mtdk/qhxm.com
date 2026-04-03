<?php
include __DIR__ . '/../db/db.php';
session_start();
$_SESSION['url'] = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = trim(htmlspecialchars($_POST['uid']) ?? '');
    $username = trim(htmlspecialchars($_POST['user_name']) ?? '');
    $department_id = trim(htmlspecialchars($_POST['department_id']) ?? '');
    $role_id = trim(htmlspecialchars($_POST['role_id']) ?? '');
    $userstate_id = trim(htmlspecialchars($_POST['userstate_id']) ?? '');

    if (empty($uid)) {
        $_SESSION['msg'] = "用户ID不能为空";
    } elseif (empty($username)) {
        $_SESSION['msg'] = "用户姓名不能为空";
    } elseif (empty($department_id)) {
        $_SESSION['msg'] = "请选择用户所属部门";
    } elseif (empty($role_id)) {
        $_SESSION['msg'] = "请选择用户权限";
    } elseif ($userstate_id == '') {
        $_SESSION['msg'] = "请选择用户状态";
    } else {
        // 提交数据
        $stmt = $dbh->prepare("update usertb set uname = :uname, department_id = :department_id, role_id = :role_id, userstate_id = :userstate_id where uid = :uid");
        $stmt->bindParam(':uname',$username);
        $stmt->bindParam(':department_id',$department_id);
        $stmt->bindParam(':role_id',$role_id);
        $stmt->bindParam(':userstate_id',$userstate_id);
        $stmt->bindParam(':uid',$uid);
        $stmt->execute();
        $affectedRows = $stmt->rowCount();

        if ($affectedRows < 0) {
            $_SESSION['msg'] = "记录修改失败了！";
        } elseif ($affectedRows > 0) {
            $_SESSION['msg'] = "记录修改成功！";
        } else {
            $_SESSION['msg'] = "没有受影响的记录！";
        }
    }
    $_SESSION['url'] = 'user_info_set.php?uid=' . base64_encode($uid);
} else {
    // err_msg
    $_SESSION['msg'] = "错误的提交方式，危险的操作，将自动退出登录！！！";
    $_SESSION['url'] = 'admin_logout.php';
}
header('location:admin_msgPage.php');
//die();