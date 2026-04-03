<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';

$repair_id = trim(base64_decode(htmlspecialchars($_GET['repair_id']))) ?? '';

if ($repair_id == '') {
    echo "<a href=device_fix_over_list.php>检修序号不能为空，请点击返回</a>";
    exit();
}
$sqlstr = "select repair_id,device_id,device_name,device_type,use_name,use_department,apply_time,brief_content";
$sqlstr .= ",auditor_name,audit_time,repairer_department,repairer_name,repair_start_time";
$sqlstr .= ",repair_end_time,repair_content from tb_device_repair_order where repair_id = :repair_id";
$stmt = $dbh->prepare($sqlstr);
$stmt->bindParam(':repair_id', $repair_id);
$stmt->execute();
$rows = $stmt->fetchAll();
if (count($rows) <= 0) {
    echo "<a href=device_fix_over_list.php>未找到检修记录，请点击返回</a>";
    exit();
}
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4">
            <div class="row">
                <div class="text-center mb-2">
                    <h3>设备维修单</h3>
                </div>
            </div>
            <form class="row g-3 needs-validation" novalidate="" action="device_repair_over_save.php" method="post">
                <div class="col-sm-2">
                    <label for="repair_id" class="form-label">报修单号</label>
                    <input type="text" class="form-control" id="repair_id" name="repair_id" value="<?php echo $rows[0]['repair_id']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="device_id" class="form-label">设备编号</label>
                    <input type="text" class="form-control" id="device_id" name="device_id" value="<?php echo $rows[0]['device_id']; ?>" readonly>
                    <input type="hidden" id="device_type" name="device_type" value="<?php echo $rows[0]['device_type']; ?>">
                </div>
                <div class="col-sm-2">
                    <label for="device_name" class="form-label">设备编号</label>
                    <input type="text" class="form-control" id="device_name" name="device_name" value="<?php echo $rows[0]['device_name']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="apply_time" class="form-label">报修日期</label>
                    <input type="text" class="form-control" id="apply_time" name="apply_time" value="<?php echo $rows[0]['apply_time']; ?>" readonly>
                </div>
                <div class="col-12">
                    <label for="brief_content" class="form-label">报修简要说明</label>
                    <textarea name="brief_content" id="brief_content" class="form-control" rows="3" maxlength="80" readonly><?php echo $rows[0]['brief_content']; ?></textarea>
                </div>
                <div class="col-sm-2">
                    <label for="use_name" class="form-label">报修人员</label>
                    <input type="text" class="form-control" name="use_name" id="use_name" value="<?php echo $rows[0]['use_name']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="use_department" class="form-label">报修部门</label>
                    <input type="text" class="form-control" name="use_department" id="use_department" value="<?php echo $rows[0]['use_department']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="apply_time" class="form-label">报修时间</label>
                    <input type="text" class="form-control" name="apply_time" id="apply_time" value="<?php echo $rows[0]['apply_time']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="auditor_name" class="form-label">审核人</label>
                    <input type="text" class="form-control" name="auditor_name" id="auditor_name" value="<?php echo $rows[0]['auditor_name']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="audit_time" class="form-label">审核时间</label>
                    <input type="text" class="form-control" name="audit_time" id="apply_time" value="<?php echo $rows[0]['audit_time']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="repairer_department" class="form-label">检修部门</label>
                    <input type="text" class="form-control" name="repairer_department" id="repairer_department" value="<?php echo $rows[0]['repairer_department']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="repairer_name" class="form-label">检修负责人</label>
                    <input type="text" class="form-control" name="repairer_name" id="repairer_name" value="<?php echo $rows[0]['repairer_name']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="repair_start_time" class="form-label">领单日期</label>
                    <input type="text" class="form-control" id="repair_start_time" name="repair_start_time" value="<?php echo $rows[0]['repair_start_time']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="repair_end_time" class="form-label">交单日期</label>
                    <input type="text" class="form-control" id="repair_end_time" name="repair_end_time" value="<?php echo $rows[0]['repair_end_time']; ?>" readonly>
                </div>
                <div class="col-12">
                    <label for="repair_content" class="form-label">检修内容</label>
                    <textarea name="repair_content" id="repair_content" class="form-control" rows="5" maxlength="80" readonly><?php echo $rows[0]['repair_content']; ?></textarea>
                </div>
                <div class="col-sm-2">
                    <label for="manager_check_time" class="form-label">确认时间</label>
                    <input type="date" class="form-control" id="manager_check_time" name="manager_check_time" value="<?php echo date('Y-m-d'); ?>" required>
                    <div class="invalid-feedback">
                        确认时间不能为空...
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="check_person" class="form-label">确认人</label>
                    <input type="text" class="form-control" name="check_person" id="check_person" value="<?php echo $uname; ?>" readonly required>
                    <div class="invalid-feedback">
                        确认人不能为空...
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary btn-sm" type="submit">确&nbsp;认&nbsp;完&nbsp;成</button>
                    &nbsp;<a class="btn btn-outline-secondary btn-sm" href="device_repair_over_list.php">返&nbsp;回</a>
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