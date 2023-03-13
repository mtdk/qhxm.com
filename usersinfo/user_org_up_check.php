<?php
include __DIR__ . '/../user_session/user_session.php';
include __DIR__ . '/../user_session/login_state.php';
include __DIR__ . '/../db/db.php';
$uid = trim($_GET['uid'] ? htmlspecialchars($_GET['uid']) : '');
if (empty($uid)) {
  $_SESSION['msg'] = '用户ID不能为空';
  $_SESSION['url'] = '/usersinfo/user_organization.php';
  header('location:../msgPage.php');
  die();
}
include __DIR__ . '/../myHeader.php';
include __DIR__ . '/../myMenu.php';

$stmt = $dbh->prepare("SELECT uid,uname,organi_name,organi_id FROM usersorganization_view where uid=:uid");
$stmt->bindParam(':uid', $uid);
$stmt->execute();
$rows = $stmt->fetch(PDO::FETCH_ASSOC);
?>
  <main class="flex-shrink-0 mt-lg-5">
    <div class="container mt-lg-4">
      <form class="row g-3 needs-validation" novalidate method="post" action="user_org_up_save.php">
        <input type="hidden" name="old_organi_id" value="<?php echo $rows['organi_id']; ?>">
        <div class="col-md-3">
          <label for="uid" class="form-label">用户编号</label>
          <input type="text" name="id" class="form-control" value="<?php echo $rows['uid']; ?>" readonly required>
          <div class="invalid-feedback">
            用户编号不能为空
          </div>
        </div>
        <div class="col-md-3">
          <label for="uname" class="form-label">用户姓名</label>
          <input type="text" name="uname" class="form-control" value="<?php echo $rows['uname']; ?>" readonly required>
          <div class="invalid-feedback">
            用户姓名不能为空
          </div>
        </div>
        <div class="col-md-3">
          <label for="organi_name" class="form-label">组织名称</label>
          <input type="text" name="organi_name" class="form-control" value="<?php echo $rows['organi_name']; ?>" readonly required>
          <div class="invalid-feedback">
            组织名称不能为空
          </div>
        </div>
        <?php
        $stmt = $dbh->prepare("select organi_id, organi_name from organizationtb order by id");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <div class="col-md-3">
          <label for="new_organi_id" class="form-label">用户姓名</label>
          <select class="form-select" id="new_organi_id" name="new_organi_id" required>
            <option selected disabled value="">请选择部门...</option>
            <?php
            foreach ($rows as $key => $value) {
              echo "<option value=" . $value['organi_id'] . ">" . $value['organi_name'] . "</option>";
            }
            ?>
          </select>
          <div class="invalid-feedback">
            请选择部门
          </div>
        </div>
        <div class="col-md-3 mt-5">
          <button class="btn btn-primary" type="submit">保&nbsp;&nbsp;存</button>
          <a href="user_organization.php" class="btn btn-success">取&nbsp;&nbsp;消</a>
        </div>
      </form>
      <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
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
    </div>
  </main>

<?php include __DIR__ . '/../myFooter.php'; ?>