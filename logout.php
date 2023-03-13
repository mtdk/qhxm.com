<?php
include __DIR__ . '/user_session/user_session.php';
$_SESSION['uid'] = '';
$_SESSION['uname'] = '';
$_SESSION['uorg'] = '';
$_SESSION['msg'] = '';
$_SESSION['url'] = '';
header('location:login.php');
