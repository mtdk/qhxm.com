<?php
include __DIR__ . '/../myHeader.php';
function getUserID(): string
{
  $microtime = substr(microtime(true), strpos(microtime(true), ".") + 1);
  $chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $userid = "";
  for ($i = 0; $i < 6; $i++) {
    $userid .= $chars[mt_rand(0, strlen($chars))];
  }

  return $microtime . strtoupper(base_convert(time() - 1420070400, 10, 36)) . $userid;
}

$userid = getUserID();
?>
<main class="flex-shrink-0">
  <div class="container mt-lg-4">
    <form class="row g-3 needs-validation" novalidate action="register_save.php" method="post">
      <div class="col-md-4">
        <label for="userid" class="form-label">用户ID</label>
        <input type="text" name="userid" class="form-control" value="<?php echo $userid; ?>" readonly
               required>
        <div class="valid-feedback">
          用户ID不能为空
        </div>
      </div>
      <div class="col-md-4">
        <label for="username" class="form-label">用户姓名</label>
        <input type="text" name="username" class="form-control" placeholder="请输入您的用户名" required>
        <div class="valid-feedback">
          用户姓名不能为空
        </div>
      </div>
      <div class="col-md-4">
        <label for="userpwd" class="form-label">用户密码</label>
        <div class="input-group has-validation">
          <input type="password" name="userpwd" class="form-control" placeholder="请输入您的6~10位密码"
                 minlength="6" maxlength="16" required>
          <div class="invalid-feedback">
            用户密码不能为空
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <label for="reuserpwd" class="form-label">确认密码</label>
        <div class="input-group has-validation">
          <input type="password" name="reuserpwd" class="form-control" placeholder="请再次输入您的密码"
                 minlength="6" maxlength="16" required>
          <div class="invalid-feedback">
            确认密码不能为空
          </div>
        </div>
      </div>
      <div class="col-12">
        <button class="btn btn-primary" type="submit">注册</button>
        <a class="btn btn-primary" href="../login.php">取消</a>
      </div>
    </form>
</main>
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