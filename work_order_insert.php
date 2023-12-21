<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';
?>
    <main class="flex-shrink-0">
        <div class="container mt-lg-4 overflow-y-scroll">
            <div class="row">
                <div class="text-center mb-2">
                    <h3>工单输入</h3>
                </div>
            </div>
            <form class="row g-3 needs-validation" novalidate="" action="work_order_save.php" method="post">
                <div class="col-sm-2">
                    <label for="pro_id" class="form-label">编号</label>
                    <input type="text" class="form-control" id="pro_id" maxlength="10" name="pro_id"
                           required>
                    <div class="invalid-feedback">
                        请输入产品编号...！
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="bath_number" class="form-label">批号</label>
                    <input type="text" class="form-control" id="bath_number" value="<?php echo date('Ymd'); ?>"
                           name="bath_number" minlength="11" maxlength="11" readonly required>
                    <div class="invalid-feedback">
                        请输入批号...！
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="bath_number_index" class="form-label">批次序号</label>
                    <input type="text" class="form-control" id="bath_number_index" name="bath_number_index"
                           minlength="3" maxlength="3" required>
                    <div class="invalid-feedback">
                        请输入批次序号...！
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="remarks" class="form-label">备注</label>
                    <input type="text" class="form-control" id="remarks" name="remarks" minlength="2" maxlength="11"
                           required>
                    <div class="invalid-feedback">
                        请输入客户名称...！
                    </div>
                </div>
                <div class="col-sm-2">
                    <label for="technology_target" class="form-label">工艺选择</label>
                    <select name="technology_target" class="form-select" id="technology_target" required>
                        <option selected disabled value="">请选择生产工艺...</option>
                        <option value="FS">分散</option>
                        <option value="YM">研磨</option>
                    </select>
                    <div class="invalid-feedback">
                        请选择生产设备...
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary btn-sm" type="submit">提&nbsp;交&nbsp;保&nbsp;存</button>
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
        <div class="container mt-lg-3">
            <table class="table table-hover text-primary">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">编号</th>
                    <th scope="col">批号</th>
                    <th scope="col">备注</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sth = $dbh->prepare("select * from work_order where work_state = 0");
                $sth->execute();
                $rows = $sth->fetchAll();
                $length = count($rows);
                for ($i = 0; $i < $length; $i++) { ?>
                    <tr>
                        <th scope="row"><?php echo $i + 1; ?></th>
                        <td>
                            <a href="#?id=<?php echo $rows[$i]['id']; ?>&pro_id=<?php echo $rows[$i]['pro_id']; ?>&bath_number=<?php echo $rows[$i]['bath_number']; ?>&remarks=<?php echo $rows[$i]['remarks']; ?>"><?php echo $rows[$i]['pro_id']; ?></a>
                        </td>
                        <td><?php echo $rows[$i]['bath_number']; ?></td>
                        <td><?php echo $rows[$i]['remarks']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

<?php include __DIR__ . '/myFooter.php'; ?>