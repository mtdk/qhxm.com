<?php
include __DIR__ . '/user_session/user_session.php';
include __DIR__ . '/user_session/login_state.php';
include __DIR__ . '/db/db.php';
include __DIR__ . '/myHeader.php';
include __DIR__ . '/myMenu.php';

$stmt = $dbh->prepare("select machine_id,machine_name from kyjsb order by id");
$stmt->execute();
$rows = $stmt->fetchAll();
?>
  <main class="flex-shrink-0">
    <div class="container mt-lg-4">
      <div class="row">
        <div class="text-center mb-2">
          <h2>空压机运行记录</h2>
        </div>
      </div>
      <form class="row g-3 needs-validation" novalidate="" action="kyjsbjl_save.php" method="post">
        <div class="col-sm-2">
          <label for="machine_id" class="form-label">处理设备选择</label>
          <select name="machine_id" class="form-select" id="machine_id" required>
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
          <label for="register_date" class="form-label">日期选择</label>
          <input type="date" class="form-control" id="register_date" value="<?php echo date('Y-m-d'); ?>"
                 name="register_date" readonly required>
          <div class="invalid-feedback">
            请选择日期...
          </div>
        </div>
        <div class="col-sm-2">
          <label for="register_time" class="form-label">开机时间</label>
          <input type="time" class="form-control" id="register_time" value="<?php echo date('H:i'); ?>"
                 name="register_time" required>
          <div class="invalid-feedback">
            请选择时间...
          </div>
        </div>
        <div class="col-sm-1">
          <label for="radio_stacked" class="form-label">开机启动</label>
          <div class="form-check mt-1">
            <input type="radio" class="form-check-input" id="radio_stacked" value="开机" name="radio_stacked"
                   required>
            <label class="form-check-label" for="radio_stacked">运行...</label>
            <div class="invalid-feedback">请选择开机运行...!</div>
          </div>
        </div>
        <div class="col-sm-1">
          <label for="radioMachineStatus" class="form-label">运行状态</label>
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" value="正常" name="radioMachineStatus" id="radioMachineStatus" required>
            <label class="form-check-label" for="radioMachineStatus">
              正常
            </label>
          </div>
        </div>
        <div class="col-sm-1">
          <label for="radioMachineStatus" class="form-label">运行状态</label>
          <div class="form-check mt-1">
            <input class="form-check-input" type="radio" value="异常" name="radioMachineStatus" id="radioMachineStatus" required>
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
        $stmt = $dbh->prepare("SELECT id,machine_id,register_time,machine_state from kyjrecords where register_date=:register_date and machine_state='开机'");
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
            <td><a href="kyjsbjl_down_check.php?id=<?php echo $rows[$i]['id']; ?>"
                   class="btn btn-outline-success">关机</a>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </main>

<?php include __DIR__ . '/myFooter.php'; ?>