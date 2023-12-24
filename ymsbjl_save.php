<?php
include_once __DIR__ . '/user_session/user_session.php';
include_once __DIR__ . '/user_session/login_state.php';
include_once __DIR__ . '/db/db.php';

/*
 * 设备开机提交
 * 判断是属于读取提交或者是手动录入提交
 * 根据不同的提交形式进行数据保存
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $work_id = trim(htmlspecialchars($_POST['work_id']) ?? '');     // 工单序号（这是一个自增量，是唯一值）
    $machine_id = trim($_POST['machine_id'] ? htmlspecialchars($_POST['machine_id']) : '');     // 设备编号
    $register_time = trim($_POST['register_time'] ? htmlspecialchars($_POST['register_time']) : '');    // 开机时间
    $register_date = trim($_POST['register_date'] ? htmlspecialchars($_POST['register_date']) : '');    // 开机日期
    $pro_id = trim($_POST['pro_id'] ? strtoupper(htmlspecialchars($_POST['pro_id'])) : '');     // 产品编号
    $bath_number = trim($_POST['bath_number'] ? htmlspecialchars($_POST['bath_number']) : '');      // 批号
    $machine_status = "开机";     // 分散设备记录表设备状态
    $work_state = 1;    // 工单状态 0 -> 1 用于关闭工单总表中工单状态

    // 检查设备是否被其他用户开启
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
    } else {
        if ($work_id == -1) {   // -1 表示手动输入的生产工单
            include_once __DIR__ . '/ymsbjl_insert_other.php';
        } else {    // 自动获取的工单
            include_once __DIR__ . '/ymsbjl_insert_auto.php';
        }
    }
    $dbh = null;
    $_SESSION['url'] = 'work_order_show.php';
    header('location:msgPage.php');
}
