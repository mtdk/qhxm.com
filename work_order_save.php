<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pro_id = trim(strtoupper(htmlspecialchars($_POST['pro_id'])) ?? '');
    $bath_number = trim(htmlspecialchars($_POST['bath_number']) ?? '');
    $bath_number_index = trim(htmlspecialchars($_POST['bath_number_index']) ?? '');
    $remarks = trim(htmlspecialchars($_POST['remarks']) ?? '');
    $technology_target = trim(htmlspecialchars($_POST['technology_target']) ?? '');
    $lot_number = trim(htmlspecialchars($_POST['lot_number']) ?? 1);

    if (empty($pro_id)) {
        $_SESSION['msg'] = '编号不能为空!';
    } elseif (empty($bath_number)) {
        $_SESSION['msg'] = '批号不能为空!';
    } elseif (empty($bath_number_index)) {
        $_SESSION['msg'] = '批次序号不能为空';
    } elseif (empty($technology_target)) {
        $_SESSION['msg'] = '生产工艺选项不能为空';
    } else {
        //  提交数据
        for ($i = 0; $i < $lot_number; $i++) {
            $bath_number_all = $bath_number . $bath_number_index;   // 批号=年月日(yyyymmdd)+序号
            $sth = $dbh->prepare("insert into work_order (pro_id,bath_number,remarks,technology_target) values(:pro_id,:bath_number_all,:remarks,:technology_target)");
            $sth->bindParam(':pro_id', $pro_id);
            $sth->bindParam('bath_number_all', $bath_number_all);
            $sth->bindParam(':remarks', $remarks);
            $sth->bindParam(':technology_target', $technology_target);
            $sth->execute();
            $affectedRows = $sth->rowCount();
        }

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