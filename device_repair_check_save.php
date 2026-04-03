<?php
include_once __DIR__ . '/user_session/user_session.php';
include_once __DIR__ . '/user_session/login_state.php';
include_once __DIR__ . '/db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 检修序号
    $repair_id = trim(htmlspecialchars($_POST['repair_id']));
    // 审核时间
    $audit_time = trim(htmlspecialchars($_POST['audit_time']));
    // 审核人
    $auditor_name = trim(htmlspecialchars($_POST['auditor_name']));
    // 单据状态
    $repair_status = 1;
    // 完成进度值
    $progress = 20;
    // 维修信息
    $repair_msg = "主管已审核，等待机修领单...";

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->beginTransaction();
    $sth = $dbh->prepare("update tb_device_repair_order set auditor_name=:auditor_name,audit_time=:audit_time,repair_status=:repair_status,progress=:progress,repair_msg=:repair_msg where repair_id=:repair_id");
    $sth->bindParam(':auditor_name', $auditor_name);
    $sth->bindParam(':audit_time', $audit_time);
    $sth->bindParam(':repair_status', $repair_status, PDO::PARAM_INT);
    $sth->bindParam(':progress', $progress, PDO::PARAM_INT);
    $sth->bindParam(':repair_id', $repair_id, PDO::PARAM_STR);
    $sth->bindParam(':repair_msg', $repair_msg);
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
    $_SESSION['url'] = 'index.php';
    header('location:msgPage.php');
} else {
    $_SESSION['msg'] = "危险！！非POST方式提交，请与管理员联系！";
    $_SESSION['url'] = 'index.php';
    header('location:msgPage.php');
}