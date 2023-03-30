<?php
include __DIR__ . '/db/ym_fs_parament.php';
if (empty($machine_id)) {
    $_SESSION['msg'] = "设备编号不能为空";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($register_date)) {
    $_SESSION['msg'] = "登记日期不能为空";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($register_time)) {
    $_SESSION['msg'] = "登记时间不能为空";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($pro_id)) {
    $_SESSION['msg'] = "产品编号不能为空";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($bath_number)) {
    $_SESSION['msg'] = "生产批号不能为空";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($machine_status)) {
    $_SESSION['msg'] = "设备当前状态不能为空";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($uid)) {
    $_SESSION['msg'] = "用户ID不能为空";
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
    die();
} else {
    $stmt = $dbh->prepare("SELECT machine_id from ymsbrecords where machine_id=:machine_id and machine_status=:machine_status");
    $stmt->bindParam(':machine_id', $machine_id);
    $stmt->bindParam(':machine_status', $machine_status);
    $stmt->execute();
    $rs = $stmt->fetchAll();
    if (count($rs) > 0) {
        $_SESSION['msg'] = $uname . "你好！由于 " . $rs[0]['machine_id'] . " 还未关机不能进行开机操作！";
        $_SESSION['url'] = 'ymsbjl.php';
        header('location:msgPage.php');
        die();
    }

    $sql = "INSERT INTO ymsbrecords (machine_id,register_time,register_date,pro_id,bath_number,machine_status,uid) VALUES (?,?,?,?,?,?,?)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue('1', $machine_id);
    $stmt->bindValue('2', $register_time);
    $stmt->bindValue('3', $register_date);
    $stmt->bindValue('4', $pro_id);
    $stmt->bindValue('5', $bath_number);
    $stmt->bindValue('6', $machine_status);
    $stmt->bindValue('7', $uid);
    $is = $stmt->execute();

    if ($is) {
        $_SESSION['msg'] = "数据已提交";
    } else {
        $_SESSION['msg'] = "数据写入失败，请与管理联系，3秒后返回录入页面";
    }
    $_SESSION['url'] = 'ymsbjl.php';
    header('location:msgPage.php');
}
