<?php
$time_now = $_POST['time_now'];
$timeA = $_POST['timeA'];
$timeB = $_POST['timeB'];
$mytime = '';
$testtime = '';
$output = array();
if ((strtotime($timeB)) > (strtotime($timeA))) {
    $mytime = intval((strtotime($timeB) - strtotime($timeA)) / 60);
    $output = array('testtime' => '', 'mytime' => $mytime);
} else {
    $mytime = intval((strtotime($time_now) - strtotime($timeA)) / 60);
    $output = array('testtime' => $time_now, 'mytime' => $mytime);
}

echo json_encode($output);

//echo $mytime;
//$data1 = null;
//$data2 = null;
//$data = '{data1:"' . $data1 . '",data2:"' . $data2 . '"}';
//echo json_encode($data);