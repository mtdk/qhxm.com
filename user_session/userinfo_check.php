<?php
include __DIR__ . '/user_session.php';
$uid = trim(htmlspecialchars($_POST['uid']));
if ($uid == $_COOKIE['remeber_myid']) {
    echo $_COOKIE['uspasswd'];
}else{
    echo '';
}