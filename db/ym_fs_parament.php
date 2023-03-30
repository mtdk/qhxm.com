<?php
/*
 * @var 研磨、分散设备登记的共有参数
 */
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/login_state.php';
include __DIR__ . '/db.php';

$machine_id = trim($_POST['machine_id'] ? htmlspecialchars($_POST['machine_id']) : '');
$register_time = trim($_POST['register_time'] ? htmlspecialchars($_POST['register_time']) : '');
$register_date = trim($_POST['register_date'] ? htmlspecialchars($_POST['register_date']) : '');
$pro_id = trim($_POST['pro_id'] ? strtoupper(htmlspecialchars($_POST['pro_id'])) : '');
$bath_number = trim($_POST['bath_number'] ? htmlspecialchars($_POST['bath_number']) : '');
$machine_status = trim($_POST['radio_stacked'] ? htmlspecialchars($_POST['radio_stacked']) : '');