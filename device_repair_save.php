<?php
include_once __DIR__ . '/user_session/user_session.php';
include_once __DIR__ . '/user_session/login_state.php';
include_once __DIR__ . '/db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 设备类型
    //$device_type = trim(htmlspecialchars($_POST['device_type']));
    // 维修单号
    $repair_id = trim(htmlspecialchars($_POST['repair_id'])) ?? '';
    // 设备编号
    $device_id = trim(htmlspecialchars($_POST['device_id'])) ?? '';
    // 设备类型
    $option = trim(htmlspecialchars($_POST['option']));
    // 设备名称
    $device_name = trim(htmlspecialchars($_POST['device_name']));
    // 上报时间
    $apply_time = htmlspecialchars($_POST['apply_time']);
    // 故障信息
    $brief_content = trim($_POST['brief_content'] ? htmlspecialchars($_POST['brief_content']) : '设备故障');
    $use_name = trim(htmlspecialchars($_POST['use_name']));
    $use_department = trim(htmlspecialchars($_POST['use_department']));

    if (empty($repair_id)) {
        $_SESSION['msg'] = "维修单号不能为空";
        $_SESSION['url'] = 'device_repair.php';
        header('location:msgPage.php');
        die();
    } elseif (empty($device_id)) {
        $_SESSION['msg'] = "故障设备编号不能为空";
        $_SESSION['url'] = 'device_repair.php';
        header('location:msgPage.php');
        die();
    }

    // 查询数据
    $sqlstr = '';
    if ($option == 'FS') {
        $sqlstr = "update fssb set machine_status = 'F' where machine_id=:device_id";
    } else if ($option == 'YM') {
        $sqlstr = "update ymsb set machine_status = 'F' where machine_id=:device_id";
    } else if ($option == 'KY') {
        $sqlstr = "update kyjsb set machine_status = 'F' where machine_id=:device_id";
    } else if ($option == 'BS') {
        $sqlstr = "update bsjsb set machine_status = 'F' where machine_id=:device_id";
    } else if ($option == 'FQ') {
        $sqlstr = "update fqsb set machine_status = 'F' where machine_id=:device_id";
    }

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $checkQuery = "select * from tb_device_repair_order where repair_id=:repair_id";
    $sth = $dbh->prepare($checkQuery);
    $sth->bindParam(':repair_id', $repair_id);
    $sth->execute();
    $result = $sth->rowCount();
    if ($result > 0) {
        $_SESSION['msg'] = "维修单号已存在";
        $_SESSION['url'] = 'device_repair.php';
        header('location:msgPage.php');
        die();
    } else {
        $dbh->beginTransaction();
        $sqlstrA = "insert into tb_device_repair_order(repair_id,device_id,device_name,device_type";
        $sqlstrA .= ",use_department,use_name,brief_content,apply_time) values(:repair_id,:device_id";
        $sqlstrA .= ",:device_name,:device_type,:use_department,:use_name,:brief_content,:apply_time)";
        $sth = $dbh->prepare($sqlstrA);
        $sth->bindParam(':repair_id', $repair_id);
        $sth->bindParam(':device_id', $device_id);
        $sth->bindParam(':device_name', $device_name);
        $sth->bindParam(':device_type', $option);
        $sth->bindParam(':use_department', $use_department);
        $sth->bindParam(':use_name', $use_name);
        $sth->bindParam(':brief_content', $brief_content);
        $sth->bindParam(':apply_time', $apply_time);
        $sth->execute();
        $sth = $dbh->prepare($sqlstr);
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
    }
} else {
    $_SESSION['msg'] = "危险！！非POST方式提交，请与管理员联系！";
}
$_SESSION['url'] = 'index.php';
header('location:msgPage.php');