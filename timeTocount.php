<?php
// 当前时间
$time_now = $_POST['time_now'];
// 开机时间
$timeA = $_POST['timeA'];
// 关机时间
$timeB = $_POST['timeB'];
// 总时长（分钟）
$mytime = '';
// 当前关机时间
$testtime = '';
// 输出值（以数组形式）
$output = array();
// 当关机时间大于开机时间
if ((strtotime($timeB)) > (strtotime($timeA))) {
    // 计算总时常
    $mytime = intval((strtotime($timeB) - strtotime($timeA)) / 60);
    $output = array('testtime' => '', 'mytime' => $mytime);
} else {    // 关机时间小于或等于开机时间，总时长为：当前系统时间 - 开机时间
    $mytime = intval((strtotime($time_now) - strtotime($timeA)) / 60);
    $output = array('testtime' => $time_now, 'mytime' => $mytime);
}

echo json_encode($output);

//echo $mytime;
//$data1 = null;
//$data2 = null;
//$data = '{data1:"' . $data1 . '",data2:"' . $data2 . '"}';
//echo json_encode($data);