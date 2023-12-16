<?php
include_once __DIR__ . '/user_session/user_session.php';
include_once __DIR__ . '/user_session/login_state.php';
include_once __DIR__ . '/db/db.php';

$machine_id = trim($_POST['machine_id'] ? htmlspecialchars($_POST['machine_id']) : '');
$register_time = trim($_POST['register_time'] ? htmlspecialchars($_POST['register_time']) : '');
$register_date = trim($_POST['register_date'] ? htmlspecialchars($_POST['register_date']) : '');
$pro_id = trim($_POST['pro_id'] ? strtoupper(htmlspecialchars($_POST['pro_id'])) : '');
$bath_number = trim($_POST['bath_number'] ? htmlspecialchars($_POST['bath_number']) : '');
$machine_status = "开机";
$work_orderid = trim(htmlspecialchars($_POST['work_orderid']) ?? '');
$work_state = 1;

if (empty($machine_id)) {
    $_SESSION['msg'] = "设备编号不能为空";
    $_SESSION['url'] = 'work_order_show.php';
    header('location:msgPage.php');
    die();
} elseif (empty($register_date)) {
    $_SESSION['msg'] = "登记日期不能为空";
    $_SESSION['url'] = 'work_order_show.php';
    header('location:msgPage.php');
    die();
} elseif (empty($register_time)) {
    $_SESSION['msg'] = "登记时间不能为空";
    $_SESSION['url'] = 'work_order_show.php';
    header('location:msgPage.php');
    die();
} elseif (empty($pro_id)) {
    $_SESSION['msg'] = "产品编号不能为空";
    $_SESSION['url'] = 'work_order_show.php';
    header('location:msgPage.php');
    die();
} elseif (empty($bath_number)) {
    $_SESSION['msg'] = "生产批号不能为空";
    $_SESSION['url'] = 'work_order_show.php';
    header('location:msgPage.php');
    die();
} elseif (empty($uid)) {
    $_SESSION['msg'] = "用户ID不能为空";
    $_SESSION['url'] = 'work_order_show.php';
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
        $_SESSION['url'] = 'work_order_show.php';
        header('location:msgPage.php');
        die();
    } else {
        $sth = $dbh->prepare("insert into fssbrecords (machine_id,register_date,register_time,pro_id,bath_number,machine_status,uid) values(:machine_id,:register_date,:register_time,:pro_id,:bath_number,:machine_status,:uid)");
        $sth->bindParam(':machine_id', $machine_id);
        $sth->bindParam(':register_date', $register_date);
        $sth->bindParam(':register_time', $register_time);
        $sth->bindParam(':pro_id', $pro_id);
        $sth->bindParam(':bath_number', $bath_number);
        $sth->bindParam(':machine_status', $machine_status);
        $sth->bindParam(':uid', $uid);
        $sth->execute();
        if (!empty($work_orderid)) {
            $sth = $dbh->prepare("update work_order set work_state = :work_state where id = :work_orderid");
            $sth->bindParam(':work_state', $work_state);
            $sth->bindParam(':work_orderid', $work_orderid);
            $sth->execute();
            try {
                $dbh->commit();
                $_SESSION['msg'] = "数据已提交";
            } catch (PDOException $e) {
                $_SESSION['msg'] = "提交失败: " . $e->getMessage();
            }
        } else {
            $affectedRows = $sth->rowCount();
            if ($affectedRows < 0) {
                $_SESSION['msg'] = "数据保存失败！";
            } elseif ($affectedRows > 0) {
                $_SESSION['msg'] = "数据保存成功！";
            } else {
                $_SESSION['msg'] = "没有受影响的数据！";
            }
        }
    }
    $dbh = null;
    $_SESSION['url'] = 'work_order_show.php';
    header('location:msgPage.php');
}
