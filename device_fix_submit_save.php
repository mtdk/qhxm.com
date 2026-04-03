<?php
include_once __DIR__ . '/user_session/user_session.php';
include_once __DIR__ . '/user_session/login_state.php';
include_once __DIR__ . '/db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 维修单号
    $repair_id = trim(htmlspecialchars($_POST['repair_id']));
    $repair_end_time = trim(htmlspecialchars($_POST['repair_end_time']));
    $repair_content = trim(htmlspecialchars($_POST['repair_content']));
    $repair_status = 3;
    $progress = 95;
    $repair_msg = "维修已完成，等待使用部门主管确认...";

    if (empty($repair_id)) {
        $_SESSION['msg'] = "报修单号不能为空";
        $_SESSION['url'] = 'device_fix_over_list.php';
        header('location:msgPage.php');
        die();
    }

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->beginTransaction();
    $sqlstr = "update tb_device_repair_order set repair_end_time=:repair_end_time,repair_content=:repair_content";
    $sqlstr .= ",repair_status=:repair_status,progress=:progress,repair_msg=:repair_msg where repair_id=:repair_id";
    $sth = $dbh->prepare($sqlstr);
    $sth->bindParam(':repair_end_time', $repair_end_time);
    $sth->bindParam(':repair_content', $repair_content);
    $sth->bindParam(':repair_status', $repair_status);
    $sth->bindParam(':progress', $progress);
    $sth->bindParam(':repair_msg', $repair_msg);
    $sth->bindParam(':repair_id', $repair_id);
    $sth->execute();
    try {
        $dbh->commit();
        $_SESSION['msg'] = "数据已提交";
    } catch (PDOException $e) {
        $dbh->rollBack();
        $_SESSION['msg'] = "提交失败：" . $e->getMessage();
    } finally {
        $dbh = null;
    }
    $_SESSION['url'] = 'device_fix_over_list.php';
    header('location:msgPage.php');
} else {
    $_SESSION['msg'] = "危险！！非POST方式提交，请与管理员联系！";
    $_SESSION['url'] = 'index.php';
    header('location:msgPage.php');
}