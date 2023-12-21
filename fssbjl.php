<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';

// 工单序号
$work_id = trim(base64_decode(htmlspecialchars($_GET['id']))) ?? '';

$stmt = $dbh->prepare("select machine_id,machine_name from fssb order by id");
$stmt->execute();
$rows = $stmt->fetchAll();
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4">
            <div class="row">
                <div class="text-center mb-2">
                    <h3>分散设备运行记录</h3>
                </div>
            </div>
            <form class="row g-3 needs-validation" novalidate="" action="fssbjl_save.php" method="post">
                <div class="col-sm-2">
                    <label for="machine_id" class="form-label">分散设备选择</label>
                    <select name="machine_id" class="form-select" id="machine_id" required>
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
                    <label for="validationTime" class="form-label">时间选择</label>
                    <input type="time" class="form-control" id="validationTime" name="register_time"
                           value="<?php echo date('H:i'); ?>" required>
                    <div class="invalid-feedback">
                        请选择时间...
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="register_date" class="form-label">日期选择</label>
                    <input type="text" class="form-control" id="register_date" value="<?php echo date('Y-m-d'); ?>"
                           name="register_date" readonly required>
                    <div class="invalid-feedback">
                        请选择日期...
                    </div>
                </div>
                <?php
                if (!empty($work_id)) {
                    $sth = $dbh->prepare("select * from work_order where id = :work_id");
                    $sth->bindParam(':work_id', $work_id);
                    $sth->execute();
                    $result = $sth->fetchAll()[0];
                }
                ?>
                <div class="col-sm-2">
                    <label for="pro_id" class="form-label">产品编号</label>
                    <?php if (!empty($result['pro_id'])): ?>
                        <input type="text" class="form-control" id="pro_id" maxlength="10" name="pro_id" value="<?php echo $result['pro_id']; ?>" required>
                    <?php else: ?>
                        <input type="text" class="form-control" id="pro_id" maxlength="10" name="pro_id" required>
                    <?php endif; ?>
                    <div class="invalid-feedback">
                        请输入产品编号...！
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="bath_number" class="form-label">批号</label>
                    <?php if (!empty($result['bath_number'])): ?>
                        <input type="text" class="form-control" id="bath_number" value="<?php echo $result['bath_number']; ?>" name="bath_number" minlength="11" maxlength="11" required>
                    <?php else: ?>
                        <input type="text" class="form-control" id="bath_number" value="<?php echo date('Ymd'); ?>" name="bath_number" minlength="11" maxlength="11" required>
                    <?php endif; ?>
                    <div class="invalid-feedback">
                        请输入批号...！
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="remarks" class="form-label">备注</label>
                    <?php if (!empty($result['remarks'])): ?>
                        <input type="text" class="form-control" id="remarks" name="remarks" value="<?php echo $result['remarks']; ?>" maxlength="11">
                    <?php else: ?>
                        <input type="text" class="form-control" id="remarks" name="remarks" maxlength="11" value="无">
                    <?php endif; ?>
                </div>
                <?php if (!empty($work_id)): ?>
                    <input type="hidden" class="form-control" id="work_id" maxlength="10" name="work_id" value="<?php echo $work_id; ?>" readonly>
                <?php else: ?>
                    <input type="hidden" class="form-control" id="work_id" maxlength="10" name="work_id" value="-1" readonly>
                <?php endif; ?>
                <?php if (!empty($result['technology_target'])): ?>
                    <input type="hidden" class="form-control" id="technology_target" maxlength="10" name="technology_target" value="<?php echo $result['technology_target']; ?>" readonly>
                <?php else: ?>
                    <input type="hidden" class="form-control" id="technology_target" maxlength="10" name="technology_target" value="FS" readonly>
                <?php endif; ?>
                <div class="col-12">
                    <button class="btn btn-primary btn-sm" type="submit">开&nbsp;机</button>
                    &nbsp;<a class="btn btn-outline-secondary btn-sm" href="work_order_show.php">返回</a>
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
        <div class="container mt-lg-4">
            <table class="table table-hover text-primary text-sm-center">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">设备编号</th>
                    <th scope="col">启动时间</th>
                    <th scope="col">当前状态</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $rd = date('Y-m-d');
                $stmt = $dbh->prepare("SELECT id,machine_id,register_time,machine_status from fssbrecords where register_date=:register_date and uid=:uid and machine_status='开机'");
                $stmt->bindParam(':register_date', $rd);
                $stmt->bindParam(':uid', $uid);
                $stmt->execute();
                $rows = $stmt->fetchAll();
                $length = count($rows);
                for ($i = 0; $i < $length; $i++) { ?>
                    <tr>
                        <th scope="row"><?php echo $i + 1; ?></th>
                        <td><?php echo $rows[$i]['machine_id']; ?></td>
                        <td><?php echo substr($rows[$i]['register_time'], 0, 5); ?></td>
                        <td><?php echo $rows[$i]['machine_status']; ?></td>
                        <td><a href="fssbjl_down_check.php?id=<?php echo $rows[$i]['id']; ?>&uid=<?php echo $uid; ?>"
                               class="btn btn-outline-success btn-sm">关机</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

<?php include __DIR__ . '/myFooter.php'; ?>