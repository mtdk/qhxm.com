<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pro_id = trim(strtoupper(htmlspecialchars($_POST['pro_id'])) ?? '');
    $bath_number = trim(htmlspecialchars($_POST['bath_number']) ?? '');
    $remarks = trim(htmlspecialchars($_POST['remarks']) ?? '');

    if (empty($pro_id)) {
        $_SESSION['msg'] = '编号不能为空!';
    } elseif (empty($bath_number)) {
        $_SESSION['msg'] = '批号不能为空!';
    } else {
        //  提交数据
        $sth = $dbh->prepare("insert into work_order (pro_id,bath_number,remarks) values(:pro_id,:bath_number,:remarks)");
        $sth->bindParam(':pro_id', $pro_id);
        $sth->bindParam('bath_number', $bath_number);
        $sth->bindParam(':remarks', $remarks);
        $sth->execute();
        $affectedRows = $sth->rowCount();

        if ($affectedRows < 0) {
            $_SESSION['msg'] = "数据保存失败！";
        } elseif ($affectedRows > 0) {
            $_SESSION['msg'] = "数据保存成功！";
        } else {
            $_SESSION['msg'] = "没有受影响的数据！";
        }
        $_SESSION['url'] = 'work_order_insert.php';
    }
} else {
    $_SESSION['msg'] = "错误的提交方式，危险的操作，将自动退出登录！！！";
    $_SESSION['url'] = 'logout.php';
}
header('location:msgPage.php');