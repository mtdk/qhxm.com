<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';

$stmt = $dbh->prepare("select machine_id,machine_name from fqsb order by id");
$stmt->execute();
$rows = $stmt->fetchAll();
?>
<main class="flex-shrink-0">
    <div class="container mt-lg-4">
        <div class="row">
            <div class="text-center mb-2">
                <h2>废气处理设施运行记录</h2>
            </div>
        </div>
        <form class="row g-3 needs-validation" novalidate="" action="fqpfsbjl_save.php" method="post">
            <div class="col-sm-2">
                <label for="validationmachine" class="form-label">处理设备选择</label>
                <select name="machine_id" class="form-select" id="validationmachine" required>
                    <option selected disabled value="">请选择...</option>
                    <?php
                    foreach ($rows as $key => $value) {
                        echo "<option value=" . $value['machine_id'] . ">" . $value['machine_name'] . "</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback">
                    请选择处理设备...
                </div>
            </div>
            <div class="col-sm-2">
                <label for="validationDatetime" class="form-label">日期选择</label>
                <input type="date" class="form-control" id="validationDatetime" value="<?php echo date('Y-m-d'); ?>"
                       name="register_date" readonly required>
                <div class="invalid-feedback">
                    请选择日期...
                </div>
            </div>
            <div class="col-sm-2">
                <label for="validationTime" class="form-label">开机时间</label>
                <input type="time" class="form-control" id="validationTime" value="<?php echo date('H:i'); ?>"
                       name="register_time" required>
                <div class="invalid-feedback">
                    请选择时间...
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">开&nbsp;机</button>
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
        <table class="table table-hover text-primary">
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
            $stmt = $dbh->prepare("SELECT id,machine_id,register_time,machine_state from fqssrecords where register_date=:register_date and machine_state='开机'");
            $stmt->bindParam(':register_date', $rd);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $length = count($rows);
            for ($i = 0; $i < $length; $i++) { ?>
                <tr>
                    <th scope="row"><?php echo $i + 1; ?></th>
                    <td><?php echo $rows[$i]['machine_id']; ?></td>
                    <td><?php echo substr($rows[$i]['register_time'], 0, 5); ?></td>
                    <td><?php echo $rows[$i]['machine_state']; ?></td>
                    <td><a href="fqpfsbjl_down_check.php?id=<?php echo $rows[$i]['id']; ?>"
                           class="btn btn-outline-success">关机</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</main>

<?php include "myFooter.php"; ?>
