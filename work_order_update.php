<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';
// 工单序号
$work_id = trim(base64_decode(htmlspecialchars($_GET['id']))) ?? '';

$stmt = $dbh->prepare("select * from work_order where id =:work_id and work_state = 0");
$stmt->bindParam(':work_id', $work_id);
$stmt->execute();
$rows = $stmt->fetchAll()[0];
if (count($rows) > 0):
    ?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4 overflow-y-scroll">
            <div class="row">
                <div class="text-center mb-2">
                    <h3>工单输入</h3>
                </div>
            </div>
            <form class="row g-3 needs-validation" novalidate="" action="work_order_update_save.php" method="post">
                <input id="work_id" name="work_id" type="hidden" value="<?php echo $rows['id']; ?>">
                <div class="col-sm-2">
                    <label for="pro_id" class="form-label">编号</label>
                    <input type="text" class="form-control" id="pro_id" maxlength="10" name="pro_id" value="<?php echo $rows['pro_id']; ?>" required>
                    <div class="invalid-feedback">
                        请输入产品编号...
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="bath_number" class="form-label">批号</label>
                    <input type="text" class="form-control" id="bath_number" value="<?php echo $rows['bath_number']; ?>" name="bath_number" minlength="11" maxlength="11" required>
                    <div class="invalid-feedback">
                        请输入批号...
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="remarks" class="form-label">备注</label>
                    <input type="text" class="form-control" id="remarks" name="remarks" value="<?php echo $rows['remarks']; ?>" minlength="2" maxlength="11" required>
                    <div class="invalid-feedback">
                        请输入客户名称...
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="technology_target" class="form-label">工艺选择</label>
                    <select name="technology_target" class="form-select" id="technology_target" required>
                        <option selected disabled value="">请选择生产工艺...</option>
                        <?php if ($rows['technology_target'] == 'FS'): ?>
                            <option value="FS" selected>分散</option>
                            <option value="YM">研磨</option>
                        <?php else: ?>
                            <option value="FS">分散</option>
                            <option value="YM" selected>研磨</option>
                        <?php endif; ?>
                    </select>
                    <div class="invalid-feedback">
                        请选择生产工艺...
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="del_work_order" name="del_work_order" value="-1">
                        <label class="form-check-label">删除工单</label>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary btn-sm" type="submit">提&nbsp;交&nbsp;保&nbsp;存</button>
                    <a href="work_order_insert.php" class="btn btn-primary btn-sm">返&nbsp;回</a>
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
<?php else: ?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4 overflow-y-scroll">
            <div class="row">
                <div class="text-center mb-2">
                    <h3>工单修改</h3>
                    <label class="text-warning text-center">工单不存在，请返回重新选择！</label>
                    <a class="btn btn-primary" href="work_order_insert.php">返&nbsp;回</a>
                </div>
            </div>
        </div>
    </main>
<?php endif;
include __DIR__ . '/myFooter.php';
?>