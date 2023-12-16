<?php
include_once __DIR__ . '/user_session/user_session.php';
include_once __DIR__ . '/user_session/login_state.php';
include_once __DIR__ . '/db/db.php';
$machine_id = trim($_POST['machine_id'] ? htmlspecialchars($_POST['machine_id']) : '');
$register_time = trim($_POST['register_time'] ? htmlspecialchars($_POST['register_time']) : '');
$register_date = trim($_POST['register_date'] ? htmlspecialchars($_POST['register_date']) : '');
$pro_id = trim($_POST['pro_id'] ? strtoupper(htmlspecialchars($_POST['pro_id'])) : '');
$bath_number = trim($_POST['bath_number'] ? htmlspecialchars($_POST['bath_number']) : '');
$machine_status = trim($_POST['radio_stacked'] ? htmlspecialchars($_POST['radio_stacked']) : '');

if (empty($machine_id)) {
    $_SESSION['msg'] = "设备编号不能为空";
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($register_date)) {
    $_SESSION['msg'] = "登记日期不能为空";
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($register_time)) {
    $_SESSION['msg'] = "登记时间不能为空";
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($pro_id)) {
    $_SESSION['msg'] = "产品编号不能为空";
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($bath_number)) {
    $_SESSION['msg'] = "生产批号不能为空";
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($machine_status)) {
    $_SESSION['msg'] = "设备当前状态不能为空";
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
    die();
} elseif (empty($uid)) {
    $_SESSION['msg'] = "用户ID不能为空";
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
    die();
} else {
    $stmt = $dbh->prepare("select machine_id from fssbrecords where machine_id = :machine_id and machine_status = :machine_status");
    $stmt->bindParam(':machine_id', $machine_id);
    $stmt->bindParam(':machine_status', $machine_status);
    $stmt->execute();
    $rs = $stmt->fetchAll();
    if (count($rs) > 0) {
        $_SESSION['msg'] = $uname . "你好！由于 " . $rs[0]['machine_id'] . " 还未关机不能进行开机操作！";
        $_SESSION['url'] = 'fssbjl.php';
        header('location:msgPage.php');
        die();
    }

    $sth = $dbh->prepare("insert into fssbrecords (machine_id,register_date,register_time,pro_id,bath_number,machine_status,uid) values(:machine_id,:register_date,:register_time,:pro_id,:bath_number,:machine_status,:uid)");
    $sth->bindParam(':machine_id',$machine_id);
    $sth->bindParam(':register_date',$register_date);
    $sth->bindParam(':register_time',$register_time);
    $sth->bindParam(':pro_id',$pro_id);
    $sth->bindParam(':bath_number',$bath_number);
    $sth->bindParam(':machine_status',$machine_status);
    $sth->bindParam(':uid',$uid);
    $sth->execute();
    $affectedRows = $sth->rowCount();

    if ($affectedRows > 0) {
        $_SESSION['msg'] = "数据已提交";
    } else {
        $_SESSION['msg'] = "数据写入失败，请与管理联系，3秒后返回录入页面";
    }
    $_SESSION['url'] = 'fssbjl.php';
    header('location:msgPage.php');
}
