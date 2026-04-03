<?php
session_start();

// 设置默认值为空字符串
$admin_uid = $_SESSION['admin_uid'] ?? '';
$admin_name = $_SESSION['admin_name'] ?? '';

// 检查消息和URL变量是否存在
$msg = $_SESSION['msg'] ?? null;
$url = $_SESSION['url'] ?? null;