<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';

$id = trim($_GET['id'] ? htmlspecialchars($_GET['id']) : '');

$stmt = $dbh->prepare("SELECT id,machine_id,register_time from fqssrecords where id=:id");
$stmt->bindParam('id', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<main class="flex-shrink-0">
    <div class="container mt-lg-4">
        <div class="row">
            <div class="text-center mb-2">
                <h2>废气处理设施运行记录</h2>
            </div>
        </div>
        <form class="row g-3 needs-validation" novalidate="" action="fqpfsbjl_down_save.php" method="post">
            <input type="hidden" value="<?php echo $result['id']; ?>" name="id">
            <div class="col-sm-2">
                <label for="validationTime" class="form-label">设备编号</label>
                <input type="text" class="form-control" id="validationTime" value="<?php echo $result['machine_id']; ?>"
                       name="machine_id" readonly required>
                <div class="invalid-feedback">
                    请选择时间...
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
                <input type="text" class="form-control" id="total_duration" value="<?php echo $total_duration; ?>"
                       name="total_duration" readonly required>
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
            <div class="col-sm-1">
                <label for="radioMachineStatus" class="form-label">运行状态</label>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="radio" value="正常" name="radioMachineStatus" required>
                    <label class="form-check-label" for="radioMachineStatus">
                        正常
                    </label>
                </div>
            </div>
            <div class="col-sm-1">
                <label for="radioMachineStatus" class="form-label">运行状态</label>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="radio" value="异常" name="radioMachineStatus" required>
                    <label class="form-check-label" for="radioMachineStatus">
                        异常
                    </label>
                    <div class="invalid-feedback">请选择运行状态...!</div>
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
</body>
</html>
