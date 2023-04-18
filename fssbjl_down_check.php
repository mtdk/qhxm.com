<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';

$id = '';
$send_uid = '';
if ($_SESSION['uorg_id'] == '' || $_SESSION['uorg_id'] != 1000) {
    $id = trim($_GET['id'] ? htmlspecialchars($_GET['id']) : '');
    $send_uid = trim($_GET['uid'] ? htmlspecialchars($_GET['uid']) : '');
    if ($id == '' || $send_uid == '') {
        $_SESSION['msg'] = '参数传递错误，请与管理员联系，3秒后跳转回登录页面';
        $_SESSION['url'] = 'fssbjl.php';
        header('location:msgPage.php');
        die();
    }
    if ($uid != $send_uid) {
        $_SESSION['msg'] = '此设备不是你开启的，你不能进行关闭操作，3秒后跳转回登录页面';
        $_SESSION['url'] = 'fssbjl.php';
        header('location:msgPage.php');
        die();
    }
} else {
    $id = trim($_GET['id'] ? htmlspecialchars($_GET['id']) : '');
}

$stmt = $dbh->prepare("SELECT id,machine_id,register_date,register_time,pro_id,bath_number from fssbrecords where id=:id");
$stmt->bindParam('id', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<main class="flex-shrink-0">
    <div class="container mt-lg-4">
        <div class="row">
            <div class="text-center mb-2">
                <h2>分散设备运行记录</h2>
            </div>
        </div>
        <form class="row g-3 needs-validation" novalidate="" action="fssbjl_down_save.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="col-sm-2">
                <label for="validationProductid" class="form-label">当前设备编号</label>
                <input type="text" class="form-control" id="validationProductid"
                       value="<?php echo $result['machine_id']; ?>" name="machine_id" readonly required>
                <div class="invalid-feedback">
                    请输入产品编号...！
                </div>
            </div>
            <div class="col-sm-2">
                <label for="validationProductid" class="form-label">产品编号</label>
                <input type="text" class="form-control" id="validationProductid"
                       value="<?php echo $result['pro_id']; ?>" name="pro_id" readonly required>
                <div class="invalid-feedback">
                    请输入产品编号...！
                </div>
            </div>
            <div class="col-sm-2">
                <label for="validationBathnumber" class="form-label">批号</label>
                <input type="text" class="form-control" id="validationBathnumber"
                       value="<?php echo $result['bath_number']; ?>" name="bath_number" readonly required>
                <div class="invalid-feedback">
                    请输入批号...！
                </div>
            </div>
            <div class="col-sm-2">
                <label for="regist_time" class="form-label">开机时间</label>
                <input type="text" class="form-control" id="regist_time"
                       value="<?php echo substr($result['register_time'], 0, 5); ?>" name="regist_time" readonly
                       required>
                <div class="invalid-feedback">
                    请输入产品编号...！
                </div>
            </div>
            <div class="col-sm-2">
                <label for="shutdown_time" class="form-label">关机时间</label>
                <?php $shutdown_time = date('H:i'); ?>
                <input type="hidden" value="<?php echo $shutdown_time; ?>" id="time_now">
                <input type="time" class="form-control" id="shutdown_time" onchange="timeCount()"
                       value="<?php echo $shutdown_time; ?>"
                       name="shutdown_time" required>
                <div class="invalid-feedback">
                    请选择时间...
                </div>
            </div>
            <div class="col-sm-2">
                <label for="total_duration" class="form-label">总时长</label>
                <?php $total_duration = intval((strtotime($shutdown_time) - strtotime($result['register_time'])) / 60); ?>
                <input type="text" class="form-control" id="total_duration"
                       value="<?php echo $total_duration; ?>" name="total_duration" readonly required>
                <div class="invalid-feedback">
                    请选择时间...
                </div>
            </div>
            <div class="col-sm-1">
                <label for="validationStartAndStop" class="form-label">关机停止</label>
                <div class="form-check mt-1">
                    <input type="radio" class="form-check-input" id="validationStop" value="关机" name="radio_stacked"
                           required>
                    <label class="form-check-label" for="validationStop">停止...</label>
                    <div class="invalid-feedback">请选择关机停止...!</div>
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">提&nbsp;交&nbsp;保&nbsp;存</button>
            </div>
            <script>
                (() => {
                    'use strict'
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    const forms = document.querySelectorAll('.needs-validation')

                    // Loop over them and prevent submission
                    Array.from(forms).forEach(form => {
                        form.addEventListener('submit', event => {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
                })()
            </script>
            <script>
                function timeCount() {
                    let time_now = $('#time_now').val();
                    let timeA = $('#regist_time').val();
                    let timeB = $('#shutdown_time').val();
                    let dt;
                    let xmlhttp;
                    if (window.XMLHttpRequest) {
                        // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        // IE6, IE5 浏览器执行代码
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState === XMLHttpRequest.DONE && xmlhttp.status === 200) {
                            dt = JSON.parse(this.responseText);
                            if (dt.testtime !== '') {
                                $('#shutdown_time').val(dt.testtime);
                                $('#total_duration').val(dt.mytime);
                            } else {
                                $('#total_duration').val(dt.mytime);
                            }
                        }
                    }
                    xmlhttp.open("POST", "timeTocount.php", true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("time_now=" + time_now + "&timeA=" + timeA + "&timeB=" + timeB);
                }
            </script>
        </form>
    </div>
</main>

<?php include __DIR__ . '/myFooter.php'; ?>

