<?php
include_once __DIR__ . '/user_session/user_session.php';
include_once __DIR__ . '/user_session/login_state.php';
include_once __DIR__ . '/db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 维修单号
    $repair_id = trim(htmlspecialchars($_POST['repair_id']));
    $device_id = trim(htmlspecialchars($_POST['device_id']));
    $device_type = trim(htmlspecialchars($_POST['device_type']));
    $manager_check_time = trim(htmlspecialchars($_POST['manager_check_time']));
    $repair_status = 4;
    $progress = 100;
    $repair_msg = "检修完成";

    if (empty($repair_id)) {
        $_SESSION['msg'] = "报修单号不能为空";
        $_SESSION['url'] = 'device_repair_over_list.php';
        header('location:msgPage.php');
        die();
    }

    $sqlstrA = '';
    if ($device_type == 'FS') {
        $sqlstrA = "update fssb set machine_status = 'T' where machine_id=:device_id";
    } else if ($device_type == 'YM') {
        $sqlstrA = "update ymsb set machine_status = 'T' where machine_id=:device_id";
    } else if ($device_type == 'KY') {
        $sqlstrA = "update kyjsb set machine_status = 'T' where machine_id=:device_id";
    } else if ($device_type == 'BS') {
        $sqlstrA = "update bsjsb set machine_status = 'T' where machine_id=:device_id";
    } else if ($device_type == 'FQ') {
        $sqlstrA = "update fqsb set machine_status = 'T' where machine_id=:device_id";
    }

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->beginTransaction();
    $sqlstr = "update tb_device_repair_order set manager_check_time=:manager_check_time";
    $sqlstr .= ",repair_status=:repair_status,progress=:progress";
    $sqlstr .= ",repair_msg=:repair_msg where repair_id=:repair_id";
    $sth = $dbh->prepare($sqlstr);
    $sth->bindParam(':manager_check_time', $manager_check_time);
    $sth->bindParam(':repair_status', $repair_status);
    $sth->bindParam(':progress', $progress);
    $sth->bindParam(':repair_msg', $repair_msg);
    $sth->bindParam(':repair_id', $repair_id);
    $sth->execute();
    $sth = $dbh->prepare($sqlstrA);
    $sth->bindParam(':device_id', $device_id);
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
    $_SESSION['url'] = 'device_repair_over_list.php';
    header('location:msgPage.php');
} else {
    $_SESSION['msg'] = "危险！！非POST方式提交，请与管理员联系！";
    $_SESSION['url'] = 'index.php';
    header('location:msgPage.php');
}