<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';

// 可将年月日时分秒毫秒加入单号中
$dateString = date('Ymd'); // 连接年月日时分秒

//echo $dateString;
// 连接数据库
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // 开启事务
    $dbh->beginTransaction();

    // 查询数据库表的最后一条记录并加锁
    $query = $dbh->query("SELECT * FROM tb_device_repair_order ORDER BY id DESC LIMIT 1 FOR UPDATE");
    $lastRecord = $query->fetch(PDO::FETCH_ASSOC);

    // 生成新的单号
    if ($lastRecord) {
        $lastId = $lastRecord['id'];
        $newId = $lastId + 1;
    } else {
        $newId = 1; // 如果表中没有记录，则从1开始
    }

    $newOrderNumber = 'WX' . $dateString . str_pad($newId, 3, '0', STR_PAD_LEFT); // 生成新的单号，例如：ORD0001

} catch (PDOException $e) {
    // 回滚事务
    $dbh->rollBack();
    echo "生成新单号时出错：" . $e->getMessage();
}

// 关闭数据库连接
$dbh = null;
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4">
            <div class="row">
                <div class="text-center mb-2">
                    <h3>设备报修申请</h3>
                </div>
            </div>
            <div class="row">
                <form class="row g-3 needs-validation" novalidate="" action="device_repair_save.php" method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="repair_id" class="form-label">维修单号</label>
                            <input class="form-control" type="text" name="repair_id" id="repair_id" value="<?php echo $newOrderNumber; ?>" readonly required>
                            <div class="invalid-feedback">
                                维修单号不能为空...
                            </div>
                        </div>
                    </div>
                    <label class="form-label">检修设备选择</label>
                    <div class="row">
                        <div class="col-sm-2">
                            <input class="form-check-input" type="radio" name="option" value="FS" onclick="searchDatabase('FS')" required>
                            <label class="form-check-label" for="inlineRadio1">分散机</label>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-check-input" type="radio" name="option" value="YM" onclick="searchDatabase('YM')" required>
                            <label class="form-check-label" for="inlineRadio2">研磨机</label>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-check-input" type="radio" name="option" value="KY" onclick="searchDatabase('KY')" required>
                            <label class="form-check-label" for="inlineRadio3">空压机</label>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-check-input" type="radio" name="option" value="BS" onclick="searchDatabase('BS')" required>
                            <label class="form-check-label" for="inlineRadio3">冰水机</label>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-check-input" type="radio" name="option" value="FQ" onclick="searchDatabase('FQ')" required>
                            <label class="form-check-label" for="inlineRadio3">废气设备</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="device_id" class="form-label">设备选择</label>
                        <select name="device_id" class="form-select" id="device_id" required>
                            <option selected disabled value="">请选择...</option>
                        </select>
                        <div class="invalid-feedback">
                            请选择生产设备...
                        </div>
                    </div>
                    <script>
                        function searchDatabase(option) {
                            var xhr = new XMLHttpRequest();
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    var result = JSON.parse(xhr.responseText);
                                    displayResults(result);
                                }
                            };
                            xhr.open("POST", "device_search.php", true);
                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhr.send("option=" + option);
                        }

                        function displayResults(results) {
                            var selectElement = document.getElementById('device_id');
                            var inputElement = document.getElementById('device_name');
                            selectElement.innerHTML = '';

                            results.forEach(function (result) {
                                var option = document.createElement('option');
                                option.value = result.machine_id;
                                option.text = result.machine_name;
                                selectElement.add(option);
                            });
                            inputElement.value = '';
                            inputElement.value = results[0].machine_name;

                            // selectElement.addEventListener('change', function() {
                            //     var selectedOption = selectElement.options[selectElement.selectedIndex];
                            //     inputElement.value = selectedOption.text;
                            // });
                        }

                        document.addEventListener("DOMContentLoaded", function () {
                            const selectElement = document.getElementById('device_id');
                            const inputElement = document.getElementById('device_name');
                            if (selectElement && inputElement) {
                                selectElement.addEventListener('change', function () {
                                    inputElement.value = selectElement.options[selectElement.selectedIndex].text;
                                });
                            } else {
                                console.error('One or more elements not found.');
                            }
                        });
                    </script>
                    <div class="col-sm-3">
                        <label class="form-label">设备名称</label>
                        <input type="text" class="form-control" id="device_name" name="device_name" readonly required>
                    </div>
                    <div class="col-sm-2">
                        <label for="apply_time" class="form-label">报修日期</label>
                        <input type="date" class="form-control" id="apply_time" name="apply_time" value="<?php echo date('Y-m-d'); ?>" required>
                        <div class="invalid-feedback">
                            报修时间不能为空...
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="brief_content" class="form-label">报修简要说明</label>
                        <textarea name="brief_content" id="brief_content" class="form-control" rows="3" placeholder="例如:设备有异响、仪表失灵、控制按钮失灵、传动系统不正常、设备震动厉害、电源故障等..." maxlength="80"></textarea>
                        <div class="invalid-feedback">
                            请填写报修简要说明...
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="use_name" class="form-label">报修人员</label>
                        <input type="text" class="form-control" name="use_name" id="use_name" value="<?php echo $uname; ?>" readonly required>
                        <div class="invalid-feedback">
                            报修人员不能为空...
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="use_department" class="form-label">报修部门</label>
                        <input type="text" class="form-control" name="use_department" id="use_department" value="<?php echo $department_name; ?>" readonly required>
                        <div class="invalid-feedback">
                            报修部门不能为空...
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary btn-sm" type="submit">提&nbsp;交&nbsp;申&nbsp;请</button>
                        &nbsp;<a class="btn btn-outline-secondary btn-sm" href="index.php">返&nbsp;回</a>
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
                </form>
            </div>
        </div>
    </main>

<?php include __DIR__ . '/myFooter.php'; ?>
