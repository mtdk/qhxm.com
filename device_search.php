<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';

$option = $_POST['option'];
try {
    // 查询数据
    $sth = '';
    if ($option == 'FS') {
        $sth = "select machine_id,machine_name from fssb where machine_status = 'T' order by id";
    } else if ($option == 'YM') {
        $sth = "select machine_id,machine_name from ymsb where machine_status = 'T' order by id";
    } else if ($option == 'KY') {
        $sth = "select machine_id,machine_name from kyjsb where machine_status = 'T' order by id";
    } else if ($option == 'BS') {
        $sth = "select machine_id,machine_name from bsjsb where machine_status = 'T' order by id";
    }else if ($option=='FQ'){
        $sth = "select machine_id,machine_name from fqsb where machine_status = 'T' order by id";
    }
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare($sth);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 返回数据以JSON格式
    echo json_encode($data);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// 关闭数据库连接
$dbh = null;