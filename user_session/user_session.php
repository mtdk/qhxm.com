<?php
/*
 * @var string $msg
 * @var string $url
 */
session_start();
$uid = $_SESSION['uid'] ?? '';
$uname = $_SESSION['uname'] ?? '';
$uorg_id = $_SESSION['uorg_id'] ?? '';
$uorg_name = $_SESSION['uorg_name'] ?? '';
$msg = $_SESSION['msg'];
$url = $_SESSION['url'];