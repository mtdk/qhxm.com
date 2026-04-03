<?php
/*
 * @var string $msg
 * @var string $url
 */
session_start();
$uid = $_SESSION['uid'] ?? '';
$uname = $_SESSION['uname'] ?? '';
$department_id = $_SESSION['department_id'] ?? '';
$department_name = $_SESSION['department_name'] ?? '';
$role_id = $_SESSION['role_id'] ?? '';
$msg = $_SESSION['msg'];
$url = $_SESSION['url'];