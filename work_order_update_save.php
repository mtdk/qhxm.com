<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $work_id = trim(htmlspecialchars($_POST['work_id']) ?? '');
    $pro_id = trim(strtoupper(htmlspecialchars($_POST['pro_id'])) ?? '');
    $bath_number = trim(htmlspecialchars($_POST['bath_number']) ?? '');
    $remarks = trim(htmlspecialchars($_POST['remarks']) ?? '');
    $technology_target = trim(htmlspecialchars($_POST['technology_target']) ?? '');
    $del_work_order = $_POST['del_work_order'];

    if (empty($work_id)) {
        $_SESSION['msg'] = '未找到工单号,请与管理员联系!';
    } elseif (empty($pro_id)) {
        $_SESSION['msg'] = '编号不能为空!';
    } elseif (empty($bath_number)) {
        $_SESSION['msg'] = '批号不能为空!';
    } elseif (empty($technology_target)) {
        $_SESSION['msg'] = '生产工艺选项不能为空';
    } else {
        //  提交数据
        if ($del_work_order == -1) {
            $sth = $dbh->prepare("update work_order set work_state = :del_work_order where id = :work_id");
            $sth->bindParam(':work_id', $work_id);
            $sth->bindParam(':del_work_order', $del_work_order);
        } else {
            $sth = $dbh->prepare("update work_order set pro_id = :pro_id,bath_number = :bath_number,remarks = :remarks,technology_target = :technology_target where id = :work_id");
            $sth->bindParam(':work_id', $work_id);
            $sth->bindParam(':pro_id', $pro_id);
            $sth->bindParam(':bath_number', $bath_number);
            $sth->bindParam(':remarks', $remarks);
            $sth->bindParam(':technology_target', $technology_target);
        }

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