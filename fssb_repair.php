<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';

$stmt = $dbh->prepare("select machine_id,machine_name from fssb where machine_status = 'T' order by id");
$stmt->execute();
$rows = $stmt->fetchAll();
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4">
            <div class="row">
                <div class="text-center mb-2">
                    <h3>分散设备报修申请</h3>
                </div>
            </div>
            <form class="row g-3 needs-validation" novalidate="" action="device_repair_save.php" method="post">
                <div class="col-sm-2">
                    <input type="hidden" id="device_type" name="device_type" value="FS">
                    <label for="device_id" class="form-label">分散设备选择</label>
                    <select name="device_id" class="form-select" id="device_id" required>
                        <option selected disabled value="">请选择...</option>
                        <?php
                        foreach ($rows as $key => $value) {
                            echo "<option value=" . $value['machine_id'] . ">" . $value['machine_name'] . "</option>";
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback">
                        请选择生产设备...
                    </div>
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
    </main>

<?php include __DIR__ . '/myFooter.php'; ?>