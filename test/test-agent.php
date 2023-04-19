<?php
$agent = $_SERVER['HTTP_USER_AGENT'];
if (stripos($agent, 'android') || stripos($agent, 'iphone')) {
    echo '这是手机端';
} else {
    echo '这是电脑端';
}