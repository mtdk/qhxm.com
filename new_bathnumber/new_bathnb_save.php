<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/login_state.php';
include __DIR__ . '/../db/db.php';

$bath_num = trim($_POST['bath_num'] ? htmlspecialchars($_POST['bath_num']) : '');
$number = trim($_POST['number'] ? htmlspecialchars($_POST['number']) : '');

if ($bath_num == '' || $number == '') {
    $_SESSION['msg'] = '参数传递错误，请联系管理员';
    $_SESSION['url'] = 'new_bathpage.php';
    header('location:../msgPage.php');
    die();
}
$bathNumber = trim($bath_num . $number);
$slq = "select count(*) as total from bath_numbertb where bath_number=:bathNumber";
$stmt = $dbh->prepare($slq);
$stmt->bindParam(':bathNumber', $bathNumber);
$stmt->execute();
$rs = $stmt->fetchAll();
if ($rs[0]['total'] != 0) {
    $_SESSION['msg'] = $uname . "你好！请不要重复提交同一个批号操作！";
    $_SESSION['url'] = 'new_bathpage.php';
    header('location:../msgPage.php');
    die();
}

$sql = "insert into bath_numbertb (bath_number) values (?)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $bathNumber);
$is = $stmt->execute();
if ($is) {
    $_SESSION['msg'] = "数据已提交";
} else {
    $_SESSION['msg'] = "数据写入失败，请与管理联系，3秒后返回录入页面";
}
$_SESSION['url'] = 'new_bathpage.php';
header('location:../msgPage.php');