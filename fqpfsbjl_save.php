<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';

/**
 * @var PDO $dbh 数据连接语句
 * @var string $sql 数据增删改查语句
 * @var string $machine_id 设备编号
 * @var string $register_date 开机日期
 * @var string $register_time 开机时间
 * @var string $shutdown_time 关机时间
 * @var int $total_duration 总时长
 * @var string $machine_status 设备运行状态(开机/关机)
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $machine_id = trim($_POST['machine_id'] ? htmlspecialchars($_POST['machine_id']) : '');
    $register_date = trim($_POST['register_date'] ? htmlspecialchars($_POST['register_date']) : '');
    $register_time = trim($_POST['register_time'] ? htmlspecialchars($_POST['register_time']) : '');
    $machine_status = "开机";
    if (empty($machine_id)) {
        $_SESSION['msg'] = "设备编号不能为空";
        $_SESSION['url'] = 'fqpfsbjl.php';
        header('location:msgPage.php');
        die();
    } elseif (empty($register_date)) {
        $_SESSION['msg'] = "登记日期不能为空";
        $_SESSION['url'] = 'fqpfsbjl.php';
        header('location:msgPage.php');
        die();
    } elseif (empty($register_time)) {
        $_SESSION['msg'] = "登记时间不能为空";
        $_SESSION['url'] = 'fqpfsbjl.php';
        header('location:msgPage.php');
        die();
    } elseif (empty($uid)) {
        $_SESSION['msg'] = "用户ID不能为空";
        $_SESSION['url'] = 'fqpfsbjl.php';
        header('location:msgPage.php');
        die();
    } else {
        $sql = "SELECT machine_id FROM fqssrecords WHERE machine_id=:machine_id and machine_status=:machine_status";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':machine_id', $machine_id);
        $stmt->bindParam(':machine_status', $machine_status);
        $stmt->execute();
        $rs = $stmt->fetchAll();
        if (count($rs) > 0) {
            $_SESSION['msg'] = $uname . "你好！由于 " . $rs[0]['machine_id'] . " 还未关机不能进行开机操作！";
            $_SESSION['url'] = 'fqpfsbjl.php';
            header('location:msgPage.php');
            die();
        }

        $sql = "INSERT INTO fqssrecords (machine_id,register_date,register_time,machine_status,uid) VALUES (?,?,?,?,?)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $machine_id);
        $stmt->bindValue(2, $register_date);
        $stmt->bindValue(3, $register_time);
        $stmt->bindValue(4, $machine_status);
        $stmt->bindValue(5, $uid);
        $is = $stmt->execute();

        if ($is) {
            $_SESSION['msg'] = "数据已提交";
        } else {
            $_SESSION['msg'] = "数据写入失败，请与管理联系，3秒后返回录入页面";
        }
        $_SESSION['url'] = 'fqpfsbjl.php';
    }
} else {
    $_SESSION['msg'] = "错误的提交方式，危险的操作，将自动退出登录！！！";
    $_SESSION['url'] = 'logout.php';
}
header('location:msgPage.php');