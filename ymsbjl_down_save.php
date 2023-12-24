<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';

// 任何一种形式的关机 work_state 状态都为1，即关机就关闭工单
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ? htmlspecialchars($_POST['id']) : '');
    $pro_id = trim(htmlspecialchars($_POST['pro_id']));
    $shutdown_time = trim(htmlspecialchars($_POST['shutdown_time']));
    $total_duration = trim(htmlspecialchars($_POST['total_duration']));
    $work_id = trim(htmlspecialchars($_POST['work_id']) ?? -1);
    $machine_status = "关机";
    $technology_target = trim(htmlspecialchars($_POST['technology_target']));
    $doing_again = trim(htmlspecialchars($_POST['doing_again']) ?? '');

    if (empty($id)) {
        $_SESSION['msg'] = "关键ID错误";
        $_SESSION['url'] = 'ymsbjl.php';
        header('location:msgPage.php');
        die();
    } elseif (empty($shutdown_time)) {
        $_SESSION['msg'] = "设备关闭时间不能为空";
        $_SESSION['url'] = 'ymsbjl.php';
        header('location:msgPage.php');
        die();
    } elseif (empty($total_duration)) {
        $_SESSION['msg'] = "总时长不能为空";
        $_SESSION['url'] = 'ymsbjl.php';
        header('location:msgPage.php');
        die();
    } else {
        $stmt = $dbh->prepare("SELECT id,machine_status from ymsbrecords where id=:id and machine_status='关机'");
        $stmt->bindParam('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $rs = $stmt->fetchAll();
        if (count($rs) > 0) {
            $_SESSION['msg'] = $uname . "你好！请不要重复提交操作！";
            $_SESSION['url'] = 'ymsbjl.php';
            header('location:msgPage.php');
            die();
        }


        // $doing_again = 1 ,表示工单需要再次生产，需要复制本条生产记录并以新纪录形式插入工单总表
        if ($doing_again == 1) {
            include_once __DIR__ . '/ymsbjl_doing_again.php';
        } else {     // 工单不再次生产，只需要更新分散记录表记录即可
            include_once __DIR__ . '/ymsbjl_over_workorder.php';
        }
        $_SESSION['url'] = 'all_device.php';
        header('location:msgPage.php');
    }
}
